import cv2
import time
import shutil
import os
import psutil

# set up the camera
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1920)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 1080)

# set up the file name and directory for the images
directory = "/home/pi/Desktop/images/"
file_prefix = "image_"

# check if the images directory exists, and create it if it doesn't
if not os.path.exists(directory):
    os.makedirs(directory)

# search for available USB drives and set up the backup directory
usb_directory = None
for drive in psutil.disk_partitions():
    if 'removable' in drive.opts:
        usb_directory = drive.mountpoint + "/images/"
        break

# main loop
while True:
    try:
        # get the current time
        current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

        # capture a frame from the camera
        ret, frame = cap.read()

        # save the frame as an image file
        file_name = directory + file_prefix + current_time + ".jpg"
        cv2.imwrite(file_name, frame)

        # backup the file to USB if available
        if usb_directory is not None:
            usb_file_name = usb_directory + file_prefix + current_time + ".jpg"
            if not os.path.exists(usb_directory):
                os.makedirs(usb_directory)
            shutil.copy(file_name, usb_file_name)

        # wait for 30 minutes before taking the next picture
        time.sleep(1800)

    except cv2.error:
        print("Camera Error.")

    except PermissionError:
        print("No USB drive not found. Backup skipped!")
        pass
