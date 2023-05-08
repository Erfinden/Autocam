#!/bin/bash

# Update and install required packages
sudo apt-get update
sudo apt-get upgrade -y
sudo apt-get install -y python3-pip nano git build-essential cmake pkg-config python3-dev apache2 php 


# Create Usb Path
sudo mkdir /media/pi/

# Clone Autocam repository to /var/www/html/
sudo git clone -v https://github.com/Erfinden/Autocam.git /var/www/html/ 

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

# Fix possible errors in Apache 
sudo dpkg --configure -a

# Edit config.json file
sudo nano /var/www/html/config.json

# Restart Avahi daemon service
sudo systemctl restart avahi-daemon.service

# Activate legacy camera automaticly 
sudo bash -c 'if grep -q "^start_x=" /boot/config.txt; then sed -i "s/^start_x=.*/start_x=1/" /boot/config.txt; else echo "start_x=1" >> /boot/config.txt; fi'
sudo bash -c 'if grep -q "^gpu_mem=" /boot/config.txt; then sed -i "s/^gpu_mem=.*/gpu_mem=128/" /boot/config.txt; else echo "gpu_mem=128" >> /boot/config.txt; fi'
sudo bash -c 'if grep -q "^camera_auto_detect=" /boot/config.txt; then sed -i "s/^camera_auto_detect=.*/#camera_auto_detect=1/" /boot/config.txt; else echo "#camera_auto_detect=1" >> /boot/config.txt; fi'


# Display success message
echo "Autocam setup complete!"
sleep 1
echo "rebooting in ...3"
sleep 1
echo "rebooting in ...2"
sleep 1
echo "rebooting in ...1"
sudo reboot
