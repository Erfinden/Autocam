# set up the USB drive backup
usb_directory = None
for root, dirs, files in os.walk('/media/pi/'):
    for dir in dirs:
        usb_directory = os.path.join(root, dir)
        break
    if usb_directory is not None:
        break

if usb_directory is None:
    print("Warning: No USB drive found.")

else:
    usb_directory = os.path.join(usb_directory, 'images')

    if not os.path.exists(usb_directory):
        os.makedirs(usb_directory)
        print("Created backup directory at {}".format(usb_directory))

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
            if usb_directory is not None:
                usb_file_name = os.path.join(usb_directory, file_prefix + current_time + ".jpg")
                shutil.copy(file_name, usb_file_name)

            # wait for 30 minutes before taking the next picture
            time.sleep(1800)

        except cv2.error:
            print("Error: Failed to capture frame from camera.")
        
        except PermissionError:
            print("Error: Permission denied. Backup unsuccessful.")
            pass
