<?php
/**
 * File: api/send-feedback.php
 * Purpose: Handles AJAX feedback form submissions for CodeGaming About page, saving user feedback to the database.
 * Features:
 *   - Sanitizes and validates feedback form input (name, email, proponent, message).
 *   - Inserts feedback into feedback_messages table.
 *   - Returns JSON response for success or error.
 * Usage:
 *   - Called via POST from the About page feedback form.
 *   - Requires Database.php and db_connection.php for DB access.
 * Included Files/Dependencies:
 *   - includes/Database.php
 *   - db_connection.php
 * Author: CodeGaming Team
 * Last Updated: July 24, 2025
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../db_connection.php';

// Helper: sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $proponent = isset($_POST['proponent']) ? sanitize($_POST['proponent']) : '';
    $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

    if (!$name || !$email || !$proponent || !$message) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }

    // Insert feedback into database
    try {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare('INSERT INTO feedback_messages (sender_name, sender_email, proponent_email, message) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $proponent, $message]);
        echo json_encode(['success' => true, 'message' => 'Feedback sent and saved!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request.']);
exit;