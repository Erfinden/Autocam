# Autocam
Autocam is a Python script designed for Raspberry Pi and similar Linux-based mini computers. Its main purpose is to automatically take pictures using a connected camera every 30 minutes. The captured images can be saved to different locations, such as a server, the device's internal storage, or an external USB device.

To achieve this functionality, Autocam utilizes the **fswebcam** command-line tool, which allows for efficient image capture. The script provides a configuration file called **config.json**, where users can easily customize various settings according to their preferences. For example, users can specify the desired video device *(e.g., "video0" or "video1")* and choose where the captured images should be stored.

By using Autocam, users can effortlessly automate the process of capturing images on their Raspberry Pi or similar devices. It proves to be a convenient solution for applications like surveillance, time-lapse photography, or monitoring systems.


## Install

Run: 
`sudo wget https://raw.githubusercontent.com/Erfinden/Autocam/main/setup.sh -O /home/setup.sh && sudo sed -i 's/#domain-name=/domain-name=/g' /etc/avahi/avahi-daemon.conf && sudo sed -i 's/^domain-name=.*/domain-name=local/g' /etc/avahi/avahi-daemon.conf && sudo chmod +x /home/setup.sh && sudo /home/setup.sh
`
Note, that you need to run this command multiple times (for me 3 times) 

## Usage

1. Connect the camera to the Raspberry Pi.
2. Connect a USB drive to the Raspberry Pi. (Optional) 
3. connect to autocam.local in your local network to access the site 
4. Upload Pictures to cloud: <br>
    -change your username and ip for the server in config.json: `sudo nano /var/www/html/config.json`
    
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
