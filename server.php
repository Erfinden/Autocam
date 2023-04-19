/////////////////////////////////////////////////////////
/////localhost server to check latest ../image/ file///// 

<?php
//// Set the directory where your images are stored ////
$directory = '/home/pi/Desktop/images/';

//// Get a list of all files in the directory ////
$files = glob($directory . '*.jpg');

//// Sort the files by newest first ////
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

//// Check if any files were found ////
if (count($files) > 0) {
    //// Get the latest file ////
    $latest_file = $files[0];
    $latest_time = filemtime($latest_file);

    //// Display the latest file information ////
    echo "Latest Picture:<br>";
    echo "Name: " . basename($latest_file) . "<br>";
    echo "Time: " . date("Y-m-d H:i:s", $latest_time) . "<br>";

    //// Display a preview of the latest image ////
    echo "<br><img src='/autocamserver/images/" . basename($latest_file) . "' alt='latest picture' style='max-width: 500px;'>";


} else {
    //// If no Files found show message ////
    echo "No files found!";
}
?>
