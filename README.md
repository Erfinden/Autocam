# Autocam
Autocam is a script for Raspberry Pi that captures an image from the camera every 30 minutes and saves it to a USB drive. It also backs up the images to the USB drive and to the desktop folder if the USB drive is not found.
># [In Depth Guide](https://github.com/nanocraftmr/Autocam/blob/main/manuel.txt)
## Install

Run: `git clone https://github.com/nanocraftmr/Autocam.git`

## Usage

1. Connect the camera to the Raspberry Pi.
2. Connect a USB drive to the Raspberry Pi.
3. Activate legacy camera, using `sudo raspi-config` 
3. Run the script using `cd /var/www/html/autocam && sudo python3 autocam.py`.
4. The script will capture an image from the camera every 30 minutes and save it to `/var/www/html/images` and backup it to the USB drive.
5. If the USB drive is not found, the images will just be saved to the images folder.
6. You may need to change the camera index in the script, e.g. `cap = cv2.VideoCapture(0)` if the camera is not detected.
7. Press Ctrl+C to stop the script.

## Requirements

- Fully Set up Raspberry Pi 
- USB drive
- OpenCV

## Setup Local Server

>make sure you have **apache2** and **php** installed

The Server is now accessible through the ip of the raspi. 
You need to be in the same wifi as the pi!
- Copy the Ip a browser. example: http://0.0.0.0
- To find out the pi adress of the raspberrypi type: `ip a`

## Start on Boot

`cd /var/www/html/autocam && sudo python3 autostart.py`

## Camera Test
 
- To test, if the camera is working: `cd /var/www/html/autocam && sudo python3 cam_test.py`



## Notes

- If the folder `images` doesn't exist on the USB drive, the script will create it automatically.
- If you encounter a permission error when running the script, make sure you have the necessary permissions to access the camera and USB drive.
