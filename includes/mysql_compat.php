<?php
/**
 * Compatibility shim: implements the legacy mysql_* API on top of PDO.
 *
 * This lets the intentionally-vulnerable testbed code (which calls the
 * removed mysql_* functions) run on modern PHP (7+). It is NOT a security
 * fix: the app's SQL is still built with string interpolation, so the
 * deliberate SQL-injection vulnerabilities remain reproducible.
 *
 * Design note: on each mysql_query() we fetch the whole result set into
 * an in-memory array and keep a cursor index. That way mysql_fetch_assoc()
 * and mysql_num_rows() both operate on that stable array instead of on a
 * shared, stateful PDOStatement (which would be consumed by the first
 * fetch and break the next one).
 */

if (!function_exists('mysql_connect')) {

    /**
     * @param mixed $result
     * @return PDO|false
     */
    function legacy_pdo() {
        global $__legacy_mysql;
        return isset($__legacy_mysql['pdo']) ? $__legacy_mysql['pdo'] : false;
    }

    function mysql_query($query) {
        global $__legacy_mysql;
        $pdo = legacy_pdo();
        if (!$pdo) {
            return false;
        }
        try {
            $stmt = $pdo->query($query);
            // Fetch the full result set so later calls never re-read a
            // consumed statement. For non-SELECTs (INSERT/UPDATE) this is
            // simply an empty array.
            $__legacy_mysql['rows'] = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: array();
            $__legacy_mysql['cursor'] = 0;
            $__legacy_mysql['affected'] = $stmt->rowCount();
        } catch (PDOException $e) {
            $__legacy_mysql['error'] = $e->getMessage();
            return false;
        }
        // Return a truthy token; the app just checks `if (!$result)`.
        return true;
    }

    function mysql_num_rows($result) {
        global $__legacy_mysql;
        return count($__legacy_mysql['rows']);
    }

    function mysql_fetch_assoc($result) {
        global $__legacy_mysql;
        if (!isset($__legacy_mysql['rows'])) {
            return null;
        }
        $i = $__legacy_mysql['cursor']++;
        if ($i < count($__legacy_mysql['rows'])) {
            return $__legacy_mysql['rows'][$i];
        }
        return null;
    }

    function mysql_get_error() {
        global $__legacy_mysql;
        return isset($__legacy_mysql['error']) ? $__legacy_mysql['error'] : '';
    }

    function mysql_connect($host, $user, $pass, $database = null) {
        global $__legacy_mysql;
        $__legacy_mysql = array(
            'rows' => array(),
            'cursor' => 0,
            'affected' => -1,
            'error' => '',
        );

        $port = null;
        if (strpos($host, ':') !== false) {
            list($host, $port) = explode(':', $host, 2);
        }

        if (!in_array('mysql', PDO::getAvailableDrivers(), true)) {
            $__legacy_mysql['error'] = 'pdo_mysql driver not available';
            return false;
        }

        $dsn = 'mysql:host=' . $host;
        if ($port !== null && $port !== '') {
            $dsn .= ';port=' . $port;
        }
        if ($database) {
            $dsn .= ';dbname=' . $database;
        }
        $dsn .= ';charset=utf8mb4';

        try {
            $__legacy_mysql['pdo'] = new PDO($dsn, (string) $user, (string) $pass, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ));
            return true;
        } catch (PDOException $e) {
            $__legacy_mysql['error'] = $e->getMessage();
            return false;
        }
    }

    function mysql_select_db($database, $link = null) {
        global $__legacy_mysql;
        $pdo = legacy_pdo();
        if (!$pdo) {
            return false;
        }
        $escaped = str_replace('`', '``', (string) $database);
        try {
            $pdo->exec('USE `' . $escaped . '`');
            return true;
        } catch (PDOException $e) {
            $__legacy_mysql['error'] = $e->getMessage();
            return false;
        }
    }

    function mysql_error($link = null) {
        return mysql_get_error();
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($str) {
        // Mirror legacy escaping (backslash, NUL, newline, CR, double-quote,
        // single-quote, control-Z) so app-level escaping behaves as before.
        return str_replace(
            array('\\', "\x00", "\n", "\r", '"', "'", "\x1a"),
            array('\\\\', '\\0', '\\n', '\\r', '\\"', '\\\'', '\\Z'),
            (string) $str
        );
    }
}
?>
