import "./bootstrap";
fetch('/api/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // other headers if needed
        },
        body: JSON.stringify({
            email: 'your_email@example.com',
            password: 'your_password',
        })
    })
    .then(response => response.json())
    .then(data => {
        // Handle response data
        console.log(data);
    })
    .catch(error => {
        // Handle errors
        console.error('Error:', error);
    });