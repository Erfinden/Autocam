import requests
import json

def update_config(username, password):
    config_file = '/var/www/html/config.json'
    with open(config_file, 'r') as f:
        config = json.load(f)
    
    config['username'] = username
    config['password'] = password

    with open(config_file, 'w') as f:
        json.dump(config, f, indent=4)

def register_user(username, password):
    url = 'http://192.168.188.193:5000/register'
    data = {'username': username, 'password': password}
    response = requests.post(url, data=data)
    return response.text

# Example usage
username = input("Username: ")
password = input("Password: ")

update_config(username, password)
response = register_user(username, password)
print(response)  # Print the response from the server
