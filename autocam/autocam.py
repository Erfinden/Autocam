import subprocess
import time
import os
import json
import requests

def capture_and_upload_image(config):
    # Set up the file name and directory for the images
    directory = config['img_dir']
    file_prefix = "image_"

    # Check if the images directory exists, and create it if it doesn't
    if not os.path.exists(directory):
        os.makedirs(directory)

    # Set up the USB drive backup
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
            # Get the current time
            current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

            # Capture an image using raspistill command
            file_name = os.path.join(directory, file_prefix + current_time + ".jpg")
            subprocess.call(["fswebcam", "--no-banner", "-S", "2", file_name])

            # Backup the file to USB if it is connected
            if usb_directory:
                usb_file_name = os.path.join(usb_directory, file_prefix + current_time + ".jpg")
                shutil.copy(file_name, usb_file_name)

            # Send the image to the server along with the username and password
            files = {'image': open(file_name, 'rb')}
            data = {
                'username': config['username'],
                'password': config['password']
            }
            response = requests.post(config['server_url'] + '/upload', files=files, data=data)

            # Check the response status
            if response.status_code == 200:
                if response.text == 'You need to register first!':
                    print('Error: You need to register first!')
                else:
                    print('Image uploaded successfully!')
            else:
                print('Failed to upload image:', response.text)

            # Wait for the specified time before taking the next picture
            time.sleep(int(config['sleep']))

        except OSError:
            print("Error: Failed to capture image using raspistill command.")
            time.sleep(1)

        except PermissionError:
            print("Error: Permission denied. USB drive not found.")
            time.sleep(1)


# Load the configuration file
with open('/var/www/html/config.json', 'r') as f:
    config = json.load(f)

# Example usage
capture_and_upload_image(config)
