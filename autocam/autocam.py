import cv2
import time
import shutil
import os

# set up the camera
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1920)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 1080)

# set up the file name and directory for the images
directory = "/var/www/html/images"
file_prefix = "image_"

# check if the images directory exists, and create it if it doesn't
if not os.path.exists(directory):
    os.makedirs(directory)

# set up the USB drive backup
usb_path = "/media/pi/"
usb_folder = next((folder for folder in os.listdir(usb_path) if os.path.isdir(os.path.join(usb_path, folder))), None)
if usb_folder:
    usb_directory = os.path.join(usb_path, usb_folder, "images")
    if not os.path.exists(usb_directory):
        os.makedirs(usb_directory)
else:
    usb_directory = None

while True:
    try:
        # get the current time
        current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

        # capture a frame from the camera
        ret, frame = cap.read()

        # save the frame as an image file
        file_name = os.path.join(directory, file_prefix + current_time + ".jpg")
        cv2.imwrite(file_name, frame)

        # backup the file to USB if it is connected
        if usb_directory:
            usb_file_name = os.path.join(usb_directory, file_prefix + current_time + ".jpg")
            shutil.copy(file_name, usb_file_name)

        # wait for 30 minutes before taking the next picture
        time.sleep(10)

    except cv2.error:
        print("Error: Failed to capture frame from camera.")
        time.sleep(1)

    except PermissionError:
        print("Error: Permission denied. USB drive not found.")
        time.sleep(1)
        pass
