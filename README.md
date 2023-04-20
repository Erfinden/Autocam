# Raspberry Pi Camera Backup Script

## Install

Run: `git clone https://github.com/nanocraftmr/Autocam.git`

## Usage

1. Connect the camera to the Raspberry Pi.
2. Connect a USB drive to the Raspberry Pi.
3. Activate legacy camera, using `sudo raspi-config` 
3. Run the script using `python3 autocam.py`.
4. The script will capture an image from the camera every 30 minutes and save it to `~/Desktop/images` and backup it to the USB drive.
5. If the USB drive is not found, the images will just be saved to the desktop folder `~/Desktop/images`.
6. You may need to change the camera index in the script, e.g. `cap = cv2.VideoCapture(0)` if the camera is not detected.
7. Press Ctrl+C to stop the script.

## Requirements

- Fully Set up Raspberry Pi 
- USB drive
- OpenCV

## Setup Local Server

1. `sudo ln -s /home/pi/Desktop/images /var/www/html/autocamserver/images`
2. `sudo cp /home/pi/Desktop/Autocam/server.php /var/www/html/autocamserver/`

The Server is now accessible through the ip of the pi. 
You need to be in the same wifi as the pi!
- ip example: "0.0.0.0/autocamserver"
- To find out the pi adress of the raspberrypi type: `ip a`

## Start on Boot

`sudo python3 autostart.py`

## Camera Test
 
- To test, if the camera is working, type: `python3 cam_test.py`

# In Depth Guide [manuel.txt](https://github.com/nanocraftmr/Autocam/blob/main/manuel.txt)


## Notes

- If the folder `images` doesn't exist on the USB drive, the script will create it automatically.
- If you encounter a permission error when running the script, make sure you have the necessary permissions to access the camera and USB drive.
