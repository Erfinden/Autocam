import requests
import json

def remove_user(config, username, password):
    url = config['server_url'] + '/remove'
    data = {'username': username, 'password': password}
    response = requests.post(url, data=data)
    return response.text

# Load the configuration file
with open('/var/www/html/config.json', 'r') as f:
    config = json.load(f)

# Example usage
username = input("Username: ")
password = input("Password: ")
confirmation = input("Are you sure you want to delete your account? (y/n): ")

if confirmation.lower() == 'y':
    response = remove_user(config, username, password)
    print(response)
else:
    print("Account deletion canceled.")
