import cv2
import time
import shutil
import os
import ansyncio
from http.server import BaseHTTPRequestHandler, HTTPServer

#### Setting up the Camara Resolution and Connection
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1920)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 1080)
###########

#### Setting the Variables for the Filename and the Folder
directory = "/home/pi/Desktop/images/"
file_prefix = "image_"
############

##### Check if the Folder already exsists, and if not create it
if not os.path.exists(directory):
    os.makedirs(directory)
#############

#### Settig up USB as a storage solution
usb_path = "/media/pi/"
usb_folder = next((folder for folder in os.listdir(usb_path) if os.path.isdir(os.path.join(usb_path, folder))), None)
if usb_folder:
    usb_directory = os.path.join(usb_path, usb_folder, "images")
    if not os.path.exists(usb_directory):
        os.makedirs(usb_directory)
else:
    usb_directory = None
########

#### Making the Main Loop for the Capturing
while True:
    try:
        # Get The current Timestamp 
        current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

        # Get an single Fram from the Camara
        ret, frame = cap.read()

        # Save the image into the Directory 
        file_name = os.path.join(directory, file_prefix + current_time + ".jpg")
        cv2.imwrite(file_name, frame)

        # Copy the Image to the USB Device
        if usb_directory:
            usb_file_name = os.path.join(usb_directory, file_prefix + current_time + ".jpg")
            shutil.copy(file_name, usb_file_name)

        # Let the code sleep for 1800 Secconds (30 Minutes)
        time.sleep(1800)

    except cv2.error:
        print("Error: Failed to capture frame from camera.")
        
    except PermissionError:
        print("Error: Permission denied. USB drive not found.")
        pass
