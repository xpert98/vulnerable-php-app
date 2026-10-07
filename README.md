# Vulnerable PHP Application - Testbed

**WARNING: This application contains intentional security vulnerabilities. DO NOT deploy to production or internet.**

## Purpose
This application is for penetration testing and security training only. Contains multiple known vulnerabilities:
- SQL Injection (login.php, search.php, profile.php)
- Reflected XSS (index.php)
- Stored XSS (profile.php comments)
- Insecure File Upload (upload.php) - no type validation, allows PHP execution
- Deprecated mysql_* functions
- No input sanitization
- No CSRF protection

## Setup
1. Configure MySQL database with vulnapp database
2. Create tables: users, comments, products
3. Ensure uploads/ directory is writable
4. Run on local test environment only

## Disclaimer
For educational purposes only. Use only on systems you own or have explicit permission to test.