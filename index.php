<!DOCTYPE html>
<html>
<head>
        <title>Autocam Webserver</title>
        <script>
            setInterval(function(){
                location.reload();
            }, 5000);
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


        </style>
</head>
<body>
        <div class="logo"></div>
        <div class="container">


        <?php
        $config = json_decode(file_get_contents('config.json'), true);
        $config_file = file_get_contents('/var/www/html/config.json');
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
            echo '<p>Status: <span style="color: ' . $status_color . ';">' . $status_text . '</span></p>';
        } else {
            // No files found
            echo 'No files found!';
        }
        ?>

        <form action="control.php" method="post">
          <?php
            $autocam_running = shell_exec("ps -ef | grep autocam.py | grep -v grep");
            $start_disabled = $autocam_running ? 'disabled' : '';
          ?>
          <button type="submit" name="action" value="start" <?php echo $start_disabled; ?>>Start</button>
          <button type="submit" name="action" value="stop">Stop</button>
        </form>

	<p>
	  IP addresses: <br>
	  <?php
	  $ips = array();
	  exec("/sbin/ifconfig | grep 'inet ' | awk '{print $2}' | grep -v '127.0.0.1'", $ips);
	  foreach ($ips as $ip) {
	    echo "$ip<br>";
	  }
	  ?>
	</p>

        </div>

</body>
</html>
