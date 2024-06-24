<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['user_id'];

// Fetch conversations
$query = "SELECT users.id, users.first_name, users.last_name, users.photo
          FROM users
          JOIN messages ON (users.id = messages.sender_id OR users.id = messages.receiver_id)
          WHERE (messages.sender_id = ? OR messages.receiver_id = ?)
          AND users.id != ?
          GROUP BY users.id, users.first_name, users.last_name, users.photo";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$stmt->execute();
$conversations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging</title>
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/loopple/loopple.css">
    <style>
        .message.sent {
            text-align: right;
        }
        .message.received {
            text-align: left;
        }
        .navbar-nav .nav-item .nav-link {
            display: flex;
            align-items: center;
        }
        .navbar-nav .nav-item .nav-link img {
            margin-right: 10px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Search form -->
           
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="ni ni-zoom-split-in"></i>
                    </a>
                </li><li class="nav-item">
            <a class="nav-link" href="dashboard.php">
    <i class="fa fa-arrow-left text-white"></i>
    <span class="nav-link-text text-white">Back to Dashboard</span>
</a>

</li>
            </ul>
            

        </div>
    </div>
</nav>
    
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white loopple-fixed-start" id="sidenav-main">
    <div class="navbar-inner">
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <?php foreach ($conversations as $conversation): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="messaging.php?user_id=<?php echo $conversation['id']; ?>">
                            <img src="<?php echo $conversation['photo']; ?>" alt="User Photo">
                            <?php echo $conversation['first_name'] . ' ' . $conversation['last_name']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="row">
        <!-- Messages Area -->
        <div class="col-md-12">
            <?php if (isset($_GET['user_id'])): ?>
                <?php
                $chat_user_id = $_GET['user_id'];

                // Fetch messages
                $query = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iiii", $current_user_id, $chat_user_id, $chat_user_id, $current_user_id);
                $stmt->execute();
                $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                ?>
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($messages)): ?>
                            <div class="alert alert-info">No messages yet. Start the conversation!</div>
                        <?php else: ?>
                            <div id="messages">
                                <?php foreach ($messages as $message): ?>
                                    <div class="message <?php echo $message['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                                        <p><?php echo $message['message']; ?></p>
                                        <small><?php echo $message['timestamp']; ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <form id="messageForm" onsubmit="sendMessage(); return false;">
                            <div class="input-group">
                                <input type="hidden" id="receiver_id" value="<?php echo $chat_user_id; ?>">
                                <input type="text" id="message" class="form-control" placeholder="Type a message">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="sendMessage()">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Select a conversation to start messaging.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function sendMessage() {
        var message = document.getElementById('message').value;
        var receiverId = document.getElementById('receiver_id').value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "send_message.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    document.getElementById('message').value = '';
                    loadMessages(); // Reload messages after sending
                } else {
                    alert('Error: ' + response.message);
                }
            }
        };
        xhr.send("message=" + message + "&receiver_id=" + receiverId);
    }

    function loadMessages() {
        var chatUserId = document.getElementById('receiver_id').value;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "load_messages.php?user_id=" + chatUserId, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('messages').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    document.getElementById('message').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Load messages for the first time if there is a chat user
    if (document.getElementById('receiver_id').value) {
        loadMessages();
    }
</script>
</body>
</html>
