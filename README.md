># [All Commands](https://github.com/nanocraftmr/Autocam/blob/main/manuel.txt)
###
# Autocam
Autocam is a script for Raspberry Pi that captures an image from the camera every 30 minutes and saves it to a USB drive. It can also if turnt on in config.json back up the images to a Usb drvie. Made for Picameras 

## Install

Run: 
`sudo wget https://raw.githubusercontent.com/Erfinden/Autocam/main/setup.sh -O /home/setup.sh && sudo sed -i 's/#domain-name=/domain-name=/g' /etc/avahi/avahi-daemon.conf && sudo sed -i 's/^domain-name=.*/domain-name=local/g' /etc/avahi/avahi-daemon.conf && sudo chmod +x /home/setup.sh && sudo /home/setup.sh
`
Note, that you need to run this command multiple times (for me 3 times) 

## Usage

1. Connect the camera to the Raspberry Pi.
2. Connect a USB drive to the Raspberry Pi. (Optional)
3. connect to autocam.local in your local network to access the site 

## Requirements

- Camera conneced to camera port
- Fully Set up Raspberry Pi 
- USB drive (optional)
- Internet 
    1. Pug in Lan Cable
    1. Setup Wifi in advanced options in the raspberry pi imager. / sudo raspi-config > Network Options > Wifi  

## Notes

- If the folder `images` doesn't exist on the USB drive or in img_dir, the script will create it automatically.
- If you encounter a permission error when running the script, make sure you have the necessary permissions to access the camera and USB drive.
