<!DOCTYPE html>
<html>
<head>
    <title>Autocam Webserver</title>
    <script>
        setInterval(function(){
            location.reload();
        }, 25000);
        function resizeTextarea() {
            const textarea = document.querySelector('.config-field');
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight - textarea.value.split('\n').length * 1.3 + 'px';
        }

        function updateConfig() {
            const textarea = document.querySelector(".config-field");
            const content = textarea.value;

            // Get the current value of the save_to_drive switch
            const saveToDriveSwitch = document.querySelector("#save-to-drive-switch");
            const saveToDriveValue = saveToDriveSwitch.checked;

            // Get the values of sleep and key inputs
            const sleepInput = document.querySelector("#sleep-input");
            const sleepValue = sleepInput.value;
            const keyInput = document.querySelector("#key-input");
            const keyValue = keyInput.value;

            // Update the config object
            const config = JSON.parse(content);
            config.save_to_drive = saveToDriveValue;
            config.sleep = sleepValue;
            config.key = keyValue;

            // Convert the updated config object back to JSON
            const updatedContent = JSON.stringify(config, null, 4);

            fetch("/update_config.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({content: updatedContent}),
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    </script>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #141414;
            font-family: Consolas, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.3);
            text-align: center;
        }
        h1 {
            margin-top: 0;
            font-size: 36px;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.3);
            margin-top: 20px;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 100px;
            height: 100px;
            background-color: #333333;
            border-radius: 50%;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.3);
            color: #FFFFFF;
            line-height: 1px;
            font-size: 24px;
            text-transform: uppercase;
            background-image: url("logo.png");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .status {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .active .status {
            color: #33CC33;
        }
        .stopped .status {
            color: #FF3333;
        }

        button {
            display: inline-block;
            background-color: #333333;
            color: #FFFFFF;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
        }
        button:hover {
            background-color: #666666;
        }
        button:focus {
            outline: none;
        }
        
        .config-field {
            display: flex;
            width: 50%;
	          min-width: 360px;
            margin: 20px auto;
            padding: 5px;
            font-family: Consolas, sans-serif;
            font-size: 14px;
            resize: none;
            overflow: auto;
            height: auto;
        }

        .update-button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #333333;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
        }

        .update-button:hover {
            background-color: #666666;
        }

        .config-text {
            position: relative;
            color: #3333FF;
            display: inline-block;
            width: auto;
        }

        .config-popup {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: max-content;
            background-color: #FFFFFF;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.3);
            z-index: 1;
            color: #333333;
            display: none;
        }

        .config-text:hover .config-popup {
            display: block;
            width: auto;
        }
    </style>

    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
    <div class="logo"></div>
    <div class="container">
        <form action="control.php" method="post">
            <?php
                $autocam_running = shell_exec("ps -ef | grep autocam.py | grep -v grep");
                $start_disabled = $autocam_running ? 'disabled' : '';
            ?>
            <button type="submit" name="action" value="start" <?php echo $start_disabled; ?>>Start</button>
            <button type="submit" name="action" value="stop">Stop</button>
        </form>

        <?php
        $config_file = '/var/www/html/config.json'; // Path to the config file

        // Function to update the config file
        function updateConfigFile($content) {
            $config_file = '/var/www/html/config.json';
            file_put_contents($config_file, $content);
        }

        // Handle the POST request for updating the config file
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_data = json_decode(file_get_contents('php://input'), true);
            $content = $post_data['content'];
            updateConfigFile($content);
            echo 'Config file updated successfully!';
            exit;
        }

        // Load the config file
        $config = json_decode(file_get_contents($config_file), true);

        $directory = $index_path . $config['img_dir']; // Set the directory where your images are stored

        $autocam_running = shell_exec("ps -ef | grep autocam.py | grep -v grep");

        // Get a list of all files in the directory
        $files = glob($directory . '*.jpg');

        // Sort the files by last modified date, with newest first
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Check if any files were found
        if (count($files) > 0) {
            // Get the latest file
            $latest_file = $files[0];
            $latest_time = filemtime($latest_file);

            // Calculate the time difference between now and the latest file
            $diff_minutes = (time() - $latest_time) / 60;

            // Determine the status text and color
            if ($autocam_running) {
                $status_text = 'Active!, last picture: ' . round($diff_minutes) . ' min';
                $status_color = 'green';
            } else {
                $status_text = 'Not Active!, last picture: ' . round($diff_minutes) . ' min';
                $status_color = 'red';
            }

            // Display the latest file information
            echo '<img src="images/' . basename($latest_file) . '" alt="Latest Picture" width="500" height="300">';
            echo '<p>Name: ' . basename($latest_file) . '</p>';
            echo '<p>Time taken: ' . date("Y-m-d H:i:s", $latest_time) . '</p>';
            echo '<p>Current time: ' . date("Y-m-d H:i:s") . '</p>';
            echo '<p>Status: <span style="color: ' . $status_color . ';"><strong>' . $status_text . '</strong></span></p>';
        } else {
            echo '<p>No images found.</p>';
        }

        // Read the config file and display its content in a textarea for editing
//        $config_content = file_get_contents($config_file);
//        echo '<textarea class="config-field" oninput="resizeTextarea()">' . htmlspecialchars($config_content) . '</textarea>';

        // Switch for "save_to_drive" option
        echo '<div class="config-switch">';
        echo '<label class="config-text" for="save-to-drive-switch">Save to Drive</label>';
        echo '<label class="switch">';
        echo '<input id="save-to-drive-switch" type="checkbox" ' . ($config['save_to_drive'] ? 'checked' : '') . '>';
        echo '<span class="slider"></span>';
        echo '</label>';
        echo '</div>';

        // JavaScript function to update the config file
        echo '<script>
          function updateConfig() {
              const textarea = document.querySelector(".config-field");
              const content = textarea.value;
          
              // Get the current value of the save_to_drive switch
              const saveToDriveSwitch = document.querySelector("#save-to-drive-switch");
              const saveToDriveValue = saveToDriveSwitch.checked;
          
              // Get the values of sleep and key inputs
              const sleepInput = document.querySelector("#sleep-input");
              const sleepValue = sleepInput.value;
              const keyInput = document.querySelector("#key-input");
              const keyValue = keyInput.value;
          
              // Update the config object
              const config = JSON.parse(content);
              config.save_to_drive = saveToDriveValue;
              config.sleep = sleepValue;
              config.key = keyValue;
          
              // Convert the updated config object back to JSON
              const updatedContent = JSON.stringify(config, null, 4);
          
              fetch("/update_config.php", {
                  method: "POST",
                  headers: {
                      "Content-Type": "application/json",
                  },
                  body: JSON.stringify({content: updatedContent}),
              })
              .then(response => response.text())
              .then(data => {
                  console.log(data);
                  location.reload();
              })
              .catch(error => {
                  console.error("Error:", error);
              });
          }
        </script>';
        ?>
        
      <div class="config-input">
          <label for="sleep-input">Sleep:</label>
          <input id="sleep-input" type="number" value="<?php echo $config['sleep']; ?>">
  
          <label for="key-input">Key:</label>
          <input id="key-input" type="text" value="<?php echo $config['key']; ?>">
      </div>        
      
      <button class="update-button" onclick="updateConfig(); location.reload();">&#10004;</button>

    </div>
</body>
</html>
