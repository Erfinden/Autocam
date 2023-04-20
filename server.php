<!DOCTYPE html>
<html>
<head>
        <title>Autocam Webserver</title>
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
                        background-image: url("/autocamserver/logo.png");
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
        </style>
</head>
<body>
        <div class="logo"><img src="/autocamserver/logo.png" alt=”logo”></div>
        <div class="container">

        <?php
        // Set the directory where your images are stored
        $directory = '/home/pi/Desktop/images/';

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
            if ($diff_minutes < 30) {
                $status_text = 'Active';
                $status_color = 'green';
            } else {
                $status_text = 'Stopped';
                $status_color = 'red';
            }

            // Display the latest file information



            echo '<h1>Latest Picture</h1>';
            echo '<img src="/autocamserver/images/' . basename($latest_file) . '" alt="Latest Picture">';
            echo '<p>Name: ' . basename($latest_file) . '</p>';
            echo '<p>Time taken: ' . date("Y-m-d H:i:s", $latest_time) . '</p>';
            echo '<p>Current time: ' . date("Y-m-d H:i:s") . '</p>';
            echo '<p>Status: <span style="color: ' . $status_color . ';">' . $status_text . '</span></p>';
            echo '</div>';
        } else {
            // No files found
            echo 'No files found!';



        }
        ?>
        </div>
</body>
</html>


