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
usb_directory = ""

# get a list of all mounted USB drives
usb_drives = [os.path.join("/media/pi", f) for f in os.listdir("/media/pi") if os.path.isdir(os.path.join("/media/pi", f))]

# find the first available USB drive and set the backup directory
for drive in usb_drives:
    try:
        test_directory = os.path.join(drive, "images")
        if os.path.exists(test_directory):
            usb_directory = os.path.join(test_directory, "")
            break
    except:
        pass

if not usb_directory:
    print("Error: USB drive not found.")

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
        if usb_directory:
            usb_file_name = usb_directory + file_prefix + current_time + ".jpg"
            shutil.copy(file_name, usb_file_name)

        # wait for 30 minutes before taking the next picture
        time.sleep(1800)

    except cv2.error:
        print("Error: Failed to capture frame from camera.")
        
    except PermissionError:
        print("Error: Permission denied. USB drive not found.")
        pass
