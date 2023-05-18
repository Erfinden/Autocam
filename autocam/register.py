import requests
import json

def update_config(username, password, server_url):
    config_file = '/var/www/html/config.json'
    with open(config_file, 'r') as f:
        config = json.load(f)

    config['username'] = username
    config['password'] = password

    with open(config_file, 'w') as f:
        json.dump(config, f, indent=4)

def register_user(config, username, password):
    server_url = config['server_url']
    url = server_url + '/register'
    data = {'username': username, 'password': password}
    response = requests.post(url, data=data)
    return response.text

# Example usage
config_file = '/var/www/html/config.json'
with open(config_file, 'r') as f:
    config = json.load(f)

username = input("Username: ")
password = input("Password: ")

response = register_user(config, username, password)
if response == 'User registered successfully.':
    update_config(username, password, config['server_url'])
    print('User registered successfully! Config file updated.')
else:
    print('User registration failed:', response)
