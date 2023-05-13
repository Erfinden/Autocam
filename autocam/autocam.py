import subprocess
import time
import os
import json

with open('/var/www/html/config.json', 'r') as f:
    config = json.load(f)

# set up the file name and directory for the images
directory = config['img_dir']
file_prefix = "image_"

# check if the images directory exists, and create it if it doesn't
if not os.path.exists(directory):
    os.makedirs(directory)

# set up the USB drive backup
if config.get('usb_backup', False):
    usb_path = config['usb_path']
    usb_folder = next((folder for folder in os.listdir(usb_path) if os.path.isdir(os.path.join(usb_path, folder))), None)
    if usb_folder:
        usb_directory = os.path.join(usb_path, usb_folder, "images")
        if not os.path.exists(usb_directory):
            os.makedirs(usb_directory)
    else:
        usb_directory = None
else:
    usb_directory = None

while True:
    try:
        # get the current time
        current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

        # capture an image using raspistill command
        file_name = os.path.join(directory, file_prefix + current_time + ".jpg")
        subprocess.call(["raspistill", "-n", "-ex", "night", "-o", file_name])

        # backup the file to USB if it is connected
        if usb_directory:
            usb_file_name = os.path.join(usb_directory, file_prefix + current_time + ".jpg")
            shutil.copy(file_name, usb_file_name)

        # wait for 30 minutes before taking the next picture
        time.sleep(int(config['sleep']))

    except OSError:
        print("Error: Failed to capture image using raspistill command.")
        time.sleep(1)

    except PermissionError:
        print("Error: Permission denied. USB drive not found.")
        time.sleep(1)
