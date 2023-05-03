<!DOCTYPE html>
<html>
  <head>
    <title>Config File</title>
    <style>
      pre {
        white-space: pre-wrap;
        font-size: 1.2em;
      }
    </style>
  </head>
  <body>
    <h1>Config File</h1>
    <a href="index.php">Back</a>
    <hr>
    <pre>
      <?php
        $config_file = '/var/www/html/config.json';
        $config_json = file_get_contents($config_file);
        $config = json_decode($config_json, true);
        echo json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      ?>
    </pre>
  </body>
</html>


