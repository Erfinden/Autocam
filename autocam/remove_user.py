import requests

def remove_user(username, password):
    url = 'http://192.168.188.193:5000/remove'
    data = {'username': username, 'password': password}
    response = requests.post(url, data=data)
    return response.text

# Example usage
username = input("Username: ")
password = input("Password: ")
confirmation = input("Are you sure you want to delete your account? (y/n): ")

if confirmation.lower() == 'y':
    response = remove_user(username, password)
    print(response)
else:
    print("Account deletion canceled.")
