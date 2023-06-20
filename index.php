import subprocess
import time
import os
import json
import requests
import shutil
import sched
from requests.exceptions import Timeout, ConnectionError, InvalidSchema

def capture_and_upload_image(config, scheduler):
    directory = config['img_dir']
    file_prefix = "image_"

    if config.get('save_to_drive', True):
        if not os.path.exists(directory):
            os.makedirs(directory)

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

    try:
        current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

        file_name = os.path.join(directory, file_prefix + current_time + ".jpg")
        video_device = config.get("video_device", "video0")  # Default to "video0" if not specified
        video_device_path = "/dev/" + video_device

        # Attempt to capture the image using fswebcam command
        try:
            subprocess.call(["fswebcam", "--no-banner", "-d", video_device_path, "-S", "2", file_name])
        except OSError:
            pass  # Ignore the "Failed to capture image using fswebcam command" error

        if usb_directory:
            usb_file_name = os.path.join(usb_directory, file_prefix + current_time + ".jpg")
            shutil.copy(file_name, usb_file_name)

        # Upload the image
        files = {'image': open(file_name, 'rb')}
        data = {
            'key': config['key']
        }

        try:
            response = requests.post(config['server_url'] + '/upload', files=files, data=data, timeout=5)
            if response.status_code == 200:
                if response.text == 'You need to register first!':
                    print('Error: You need to register first!')
                else:
                    print('Image uploaded successfully!')
            else:
                print('Failed to upload image:', response.text)
        except (Timeout, ConnectionError):
            print('Error: Cant connect to Server')
        except InvalidSchema:
            print('Error: Invalid server IP.')

    except PermissionError:
        print("Error: Permission denied. USB drive not found.")

    # Schedule the deletion of the image if save_to_drive is False
    if not config.get('save_to_drive', True):
        def delete_image():
            if os.path.exists(file_name):
                os.remove(file_name)
                print('Image deleted.')

        scheduler.enter(1, 1, delete_image)

    # Schedule the next capture after the specified delay
    delay = int(config['sleep'])
    scheduler.enter(delay, 1, capture_and_upload_image, (config, scheduler))


# Load the configuration file
with open('/var/www/html/config.json', 'r') as f:
    config = json.load(f)

# Initialize the scheduler
scheduler = sched.scheduler(time.time, time.sleep)

# Schedule the initial image capture
scheduler.enter(0, 1, capture_and_upload_image, (config, scheduler))

# Run the scheduler
scheduler.run()
