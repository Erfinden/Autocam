import os

# set the full path to your Python script here
script_path = "/home/pi/Desktop/Autocam/autocam.py"

# add the command to start your Python script to /etc/rc.local
try:
    with open("/etc/rc.local", "r+") as f:
        content = f.read()
        if not script_path in content:
            f.seek(0, 2)
            f.write("\n# start Autocam on boot\n")
            f.write("python3 " + script_path + " &\n")
            f.write("# end Autocam on boot\n")
except Exception as e:
    print("Error adding script to rc.local:", e)
    sys.exit(1)

# reboot the Raspberry Pi to test the script
os.system("sudo reboot")
