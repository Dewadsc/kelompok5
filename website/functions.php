<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }  

    function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function validateCsrfToken($token) {
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    function preventBruteForce($username) {
        if (!isset($_SESSION['attempts'][$username])) {
            $_SESSION['attempts'][$username] = ['count' => 0, 'time' => time()];
        }

        if (time() - $_SESSION['attempts'][$username]['time'] > 300) {
            $_SESSION['attempts'][$username] = ['count' => 0, 'time' => time()];
        }

        return $_SESSION['attempts'][$username]['count'] < 5;
    }

    function recordFailedAttempt($username) {
        $_SESSION['attempts'][$username]['count']++;
    }

    function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }
?>
