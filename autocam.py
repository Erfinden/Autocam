import cv2
import time

# set up the camera
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1920)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 1080)
cap.set(cv2.CAP_PROP_MODE, 1)

# set up the file name and directory for the images
directory = "/home/pi/Desktop/images/"
file_prefix = "image_"

while True:
    # get the current time
    current_time = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime())

    # capture a frame from the camera
    ret, frame = cap.read()

    # save the frame as an image file
    file_name = directory + file_prefix + current_time + ".jpg"
    cv2.imwrite(file_name, frame)

    # wait for 30 minutes before taking the next picture
    time.sleep(1800)

