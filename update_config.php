<?php
// Path to the config file
$config_file = '/var/www/html/config.json';

// Function to update the config file
function updateConfigFile($content) {
    global $config_file;
    file_put_contents($config_file, $content);
}

// Handle the POST request for updating the config file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = json_decode(file_get_contents('php://input'), true);
    $content = $post_data['content'];

    // Update the save_to_drive option
    $config = json_decode($content, true);
    $save_to_drive = isset($config['save_to_drive']) ? $config['save_to_drive'] : false;
    $config['save_to_drive'] = $save_to_drive;

    // Convert the updated config back to JSON
    $updated_content = json_encode($config, JSON_PRETTY_PRINT);

    updateConfigFile($updated_content);
    echo 'Config file updated successfully!';
    exit;
}
?>
