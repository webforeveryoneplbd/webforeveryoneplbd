<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">User not logged in.</div>';
    exit();
}

$current_user_id = $_SESSION['user_id'];
$chat_user_id = $_GET['user_id'];

$query = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $current_user_id, $chat_user_id, $chat_user_id, $current_user_id);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($messages)) {
    echo '<div class="alert alert-info">No messages yet. Start the conversation!</div>';
} else {
    foreach ($messages as $message) {
        echo '<div class="message ' . ($message['sender_id'] == $current_user_id ? 'sent' : 'received') . '">';
        echo '<p>' . $message['message'] . '</p>';
        echo '<small>' . $message['timestamp'] . '</small>';
        echo '</div>';
    }
}

$stmt->close();
$conn->close();
?>
