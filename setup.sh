#!/bin/bash

# Update and install required packages
sudo apt-get update
sudo apt-get upgrade -y
sudo apt-get install -y python3-pip nano git libopencv-dev python3-opencv build-essential cmake pkg-config libjpeg-dev libtiff5-dev libjasper-dev libpng-dev libavcodec-dev libavformat-dev libswscale-dev libv4l-dev libxvidcore-dev libx264-dev libfontconfig1-dev libcairo2-dev libgdk-pixbuf2.0-dev libpango1.0-dev libgtk2.0-dev libgtk-3-dev libatlas-base-dev gfortran libhdf5-dev libhdf5-serial-dev libhdf5-103 python3-pyqt5 python3-dev apache2 php 


# Clone Autocam repository to /var/www/html/
sudo git clone https://github.com/Erfinden/Autocam.git /var/www/html/

# Remove default Apache2 index page
sudo rm /var/www/html/index.html

# Update permissions for Autocam.py script and make it executable
sudo chown www-data:www-data /var/www/html/autocam/autocam.py
sudo chmod +x /var/www/html/autocam/autocam.py

# Add www-data user to sudoers file with permissions to run Autocam.py and pkill command
echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/python3 /var/www/html/autocam/autocam.py, /usr/bin/pkill -f autocam.py" | sudo tee -a /etc/sudoers

# Edit avahi-daemon.conf file to change domain name
sudo sed -i 's/#domain-name=/domain-name=/g' /etc/avahi/avahi-daemon.conf && sudo sed -i 's/^domain-name=.*/domain-name=local/g' /etc/avahi/avahi-daemon.conf

# Edit hostname for custom .local ip
sudo raspi-config nonint do_hostname autocam

# Edit config.json file
sudo nano /var/www/html/config.json

# Restart Avahi daemon service
sudo systemctl restart avahi-daemon.service

# Activate legacy camera
sudo vcgencmd enable_camera

# Display success message
echo "Autocam setup complete!"
sleep 1
echo "rebooting in ...3"
sleep 1
echo "rebooting in ...2"
sleep 1
echo "rebooting in ...1"
sudo reboot
