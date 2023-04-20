import cv2
import time
import shutil
import os
import asyncio
from threading import Thread
from http.server import BaseHTTPRequestHandler, HTTPServer

#### Colors for console Output
class bcolors:
    HEADER = '\033[95m'
    OKBLUE = '\033[94m'
    OKCYAN = '\033[96m'
    OKGREEN = '\033[92m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'
##########

#### Setting up the Camara Resolution and Connection
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1920)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 1080)
###########

#### Create Config file if it dosn't exsist 
if not 'Config.json' in os.listdir():
    open('Config.json', 'w').write('{"usb:"True","save_direct":"/home/pi/Desktop/images/","file_prefix":"image_"}')
    print(bcolors.WARNING + "Warning: Config has been created. Restart The bot after filling info" + bcolors.ENDC)
############

#### Read in Config File
ConfigFile = json.loads(open("Config.json", "r").read())
############

if (ConfigFile['usb'] == "True"):
    USBSave = True

#### Setting the Variables for the Filename and the Folder
if (USBSave == True):
    usb_path = "/media/pi/"
directory = ConfigFile['save_direct']
file_prefix = ConfigFile['file_prefix']
running = True
############

##### Check if the Folder already exsists, and if not create it
if not os.path.exists(directory):
    os.makedirs(directory)
#############

#### Settig up USB as a storage solution
usb_folder = next((folder for folder in os.listdir(usb_path) if os.path.isdir(os.path.join(usb_path, folder))), None)
if usb_folder:
    usb_directory = os.path.join(usb_path, usb_folder, "images")
    if not os.path.exists(usb_directory):
        os.makedirs(usb_directory)
else:
    usb_directory = None
########

#### Defining Webserver
class MyServer(BaseHTTPRequestHandler):
    def do_GET(self):
        self.send_response(200)
        self.send_header("Content-type", "text/html")
        self.end_headers()
        self.wfile.write(bytes("<html><head><title>AutoCam</title></head>", "utf-8"))
        self.wfile.write(bytes("<body>", "utf-8"))
        self.wfile.write(bytes("<p>Test</p>", "utf-8"))
        self.wfile.write(bytes("</body></html>", "utf-8"))
########

#### Webserver Function to start it 
def WebServerStart():
    webServer = HTTPServer((hostName, serverPort), MyServer)
    print("Server started http://%s:%s" % (hostName, serverPort))
#########

#### Making the Main Loop for the Capturing
while (running == True):
    thread = Thread(target=WebServerStart())
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

        # Let the code sleep for 1800 Secconds (30Minutes)
        print(bcolors.OKGREEN + "Image Captured at {current_time} " + bcolors.ENDC)
        time.sleep(1800)

    except cv2.error:
        print(bcolors.FAIL + "Error: Failed to capture frame from camera." + bcolors.ENDC)
        
    except PermissionError:
        print(bcolors.FAIL + "Error: Permission denied. USB drive not found." + bcolors.ENDC)
        pass

