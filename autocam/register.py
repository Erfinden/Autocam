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

def register_user(username, password, server_url):
    url = server_url + '/register'
    data = {'username': username, 'password': password}
    response = requests.post(url, data=data)
    return response.text

# Example usage
username = input("Username: ")
password = input("Password: ")

# Retrieve the server_url from your config file
config_file = '/var/www/html/config.json'
with open(config_file, 'r') as f:
    config = json.load(f)
    server_url = config['server_url']

update_config(username, password, server_url)
response = register_user(username, password, server_url)
print(response)  # Print the response from the server
