<?php
// Define your bot token
define('BOT_TOKEN', '7044008485:AAFumsBebU5m9GscuMuxC27cdBpWqTrLH7Q');

// Define the base URL of your Telegram API
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

// Include the database connection file
include 'admin/db_connect.php';

// Get the raw POST data
$update = file_get_contents('php://input');

// Decode the JSON data
$update = json_decode($update, true);

// Extract the message
$message = $update['message'];

// Check if the message contains a command
if (isset($message['text'])) {
    $text = $message['text'];
    
    // Check if the message is a command
    if (strpos($text, '/') === 0) {
        // Process commands
        switch ($text) {
            case '/izin':
            case '/sakit':
                // Prompt user to provide a reason
                sendMessage($message['chat']['id'], "Please reply to this message provide a reason for your request:");
                break;
            default:
                // Unrecognized command
                sendMessage($message['chat']['id'], "Unrecognized command. Say what?");
        }
    } else {
        // Check if the user is in the middle of an /izin or /sakit request
        if (isset($message['reply_to_message']) && isset($message['reply_to_message']['text']) && strpos($message['reply_to_message']['text'], "Please reply to this message provide a reason for your request:") !== false) {
            // Extract Telegram user ID
            $telegram_user_id = $message['from']['id'];

            // Look up the corresponding user ID from the telegram_user_mapping table
            $mapping_query = $conn->prepare("SELECT user_id FROM telegram_user_mapping WHERE telegram_user_id = ?");
            $mapping_query->bind_param("i", $telegram_user_id);
            $mapping_query->execute();
            $mapping_result = $mapping_query->get_result();
            
            // Check if mapping exists
            if ($mapping_result->num_rows > 0) {
                $mapping_row = $mapping_result->fetch_assoc();
                $user_id = $mapping_row['user_id'];

                // Extract date (assuming date is mentioned in the message)
                preg_match('/\b(?:\d{4}-\d{2}-\d{2})\b/', $text, $matches);
                $request_date = !empty($matches) ? $matches[0] : date('Y-m-d');
                
                // Extract request type
                $izin_sakit_type = $text === '/izin' ? 'Izin' : 'Sakit';
                
                // Extract reason comment
                $request_comment = $text;
                
                // Insert the Izin/Sakit request into the database
                $insert_query = $conn->prepare("INSERT INTO izin_sakit_requests (user_id, izin_sakit_type, request_date, request_comment) VALUES (?, ?, ?, ?)");
                $insert_query->bind_param("isss", $user_id, $izin_sakit_type, $request_date, $request_comment);

                if ($insert_query->execute()) {
                    // Request submitted successfully
                    sendMessage($message['chat']['id'], "Your $izin_sakit_type request for $request_date with reason '$request_comment' has been submitted successfully.");
                } else {
                    // Error submitting request
                    sendMessage($message['chat']['id'], "Error submitting $izin_sakit_type request. Please try again.");
                }
            } else {
                // Mapping not found
                sendMessage($message['chat']['id'], "Your Telegram account is not mapped to any user. Please contact the administrator.");
            }
        }
    }
}

// Function to send messages via Telegram API
function sendMessage($chat_id, $message_text) {
    $url = API_URL.'sendMessage?chat_id='.$chat_id.'&text='.urlencode($message_text);
    file_get_contents($url);
}
?>
