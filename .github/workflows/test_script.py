import requests

# Use a dummy token for testing
token = 'dummy-token-for-testing'  # Change to the actual token when testing
headers = {'Authorization': f'token {token}'}

# Example API call to create a test issue (replace with your repo details)
response = requests.post(
    'https://api.github.com/repos/your-username/your-repo/issues',
    headers=headers,
    json={"title": "Test Issue", "body": "This is a test issue."}
)

# Print the response
print(response.status_code, response.json())
