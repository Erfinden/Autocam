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

def login_user(config, username, password):
    server_url = config['server_url']
    url = server_url + '/login'
    data = {'username': username, 'password': password}
    response = requests.post(url, data=data)
    return response.text

# Example usage
config_file = '/var/www/html/config.json'
with open(config_file, 'r') as f:
    config = json.load(f)

username = input("Username: ")
password = input("Password: ")

response = login_user(config, username, password)
if response == 'Login successful!':
    update_config(username, password, config['server_url'])
    print('Login successful! Config file updated.')
else:
    print('Login failed: Username not found. Please register! \n', response)
