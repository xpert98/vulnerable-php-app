<?php
// One-shot DB seeder for prebuilt deploys (no host repo mount required).
// Runs from the app image: php /var/www/html/db/seed.php
// - Reads MYSQL_HOST/USER/PASS/DB like config/db.php
// - Waits for MySQL, executes db/init.sql idempotently
// - users/products use ON DUPLICATE KEY UPDATE (safe to re-run)
// - comments have no unique key, so the comments INSERT is skipped
//   when the table is already seeded (avoids duplicates on restart).

$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASS') ?: '';
$db   = getenv('MYSQL_DB') ?: 'vulnapp';

$pdo = null;
$lastErr = '';
for ($i = 0; $i < 60; $i++) {
    try {
        $pdo = new PDO(
            "mysql:host={$host};charset=utf8mb4",
            (string) $user,
            (string) $pass,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
        break;
    } catch (PDOException $e) {
        $lastErr = $e->getMessage();
        fwrite(STDOUT, "seed: waiting for MySQL ({$host})… attempt " . ($i + 1) . "/60\n");
        sleep(2);
    }
}
if (!$pdo) {
    fwrite(STDERR, "seed: could not connect to MySQL: {$lastErr}\n");
    exit(1);
}

$sqlFile = __DIR__ . '/init.sql';
$sql = @file_get_contents($sqlFile);
if ($sql === false) {
    fwrite(STDERR, "seed: could not read init.sql\n");
    exit(1);
}

// Check whether comments are already seeded (no unique key → must not re-insert).
$commentsSeeded = false;
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$db}`");
    // Tables may not exist yet on first boot — check defensively.
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('comments', (array) $tables, true)) {
        $n = (int) $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
        $commentsSeeded = $n > 0;
    }
} catch (PDOException $e) {
    fwrite(STDERR, "seed: precheck failed: " . $e->getMessage() . "\n");
    exit(1);
}

// Split init.sql into statements on ";\n" boundaries (file is authored for this).
$statements = preg_split('/;\s*\n/', $sql);
$skipped = 0;
$ran = 0;
foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '') {
        continue;
    }
    // Skip comment seed on restarts to stay idempotent.
    if ($commentsSeeded && stripos($stmt, 'INSERT INTO comments') !== false) {
        $skipped++;
        continue;
    }
    // Skip pure comment lines embedded in statement chunks.
    $lines = array();
    foreach (explode("\n", $stmt) as $line) {
        if (preg_match('/^\s*--/', $line)) {
            continue;
        }
        $lines[] = $line;
    }
    $stmt = trim(implode("\n", $lines));
    if ($stmt === '') {
        continue;
    }
    // One line per statement so failures are attributable in `compose logs`.
    $preview = preg_replace('/\s+/', ' ', substr($stmt, 0, 80));
    try {
        $pdo->exec($stmt);
        $ran++;
        fwrite(STDOUT, "seed: [{$ran}] ok: {$preview}\n");
    } catch (PDOException $e) {
        fwrite(STDERR, "seed: [{$ran}] FAILED: {$preview} :: " . $e->getMessage() . "\n");
        exit(1);
    }
}

echo "seed: ok (ran {$ran} statements" . ($skipped ? ", skipped {$skipped} comment seeds" : "") . ")\n";

// Self-check: fail loudly instead of serving an app with an empty DB.
// (Without this, a silent skip would surface later as "Invalid credentials".)
try {
    $users = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $products = (int) $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
} catch (PDOException $e) {
    fwrite(STDERR, "seed: verification query failed: " . $e->getMessage() . "\n");
    exit(1);
}
if ($users === 0 || $products === 0) {
    fwrite(STDERR, "seed: FAILED verification (users={$users}, products={$products}) — refusing to report success\n");
    exit(1);
}
echo "seed: verified (users={$users}, products={$products})\n";
