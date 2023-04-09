import cv2
import time
import shutil
import os

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

# set up the USB drive backup
usb_directory = "/media/pi/USB/images/"

while True:
    try:
        # get the current time
        current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

        # capture a frame from the camera
        ret, frame = cap.read()

        # save the frame as an image file
        file_name = directory + file_prefix + current_time + ".jpg"
        cv2.imwrite(file_name, frame)

        # backup the file to USB if it is connected
        if os.path.exists("/media/pi/USB"):
            usb_file_name = usb_directory + file_prefix + current_time + ".jpg"
            shutil.copy(file_name, usb_file_name)

        # wait for 30 minutes before taking the next picture
        time.sleep(1800)

    except cv2.error:
        print("Camera not found")
        
    except PermissionError:
        print("USB drive not found. Backup skipped!")
        pass
