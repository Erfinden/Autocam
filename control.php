<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  if ($action == 'start') {
    $output = shell_exec('sudo /usr/bin/python /var/www/html/autocam/autocam.py');
    echo "Started process $output";
  } elseif ($action == 'stop') {
    $output = shell_exec('sudo pkill -f autocam.py');
    echo "Stopped process";
  }
}
