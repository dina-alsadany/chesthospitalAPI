<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Frontend with Ngrok</title>
</head>
<body>
  <h1>Fetch Data from Backend</h1>
  <button id="fetchButton">Fetch Data</button>
  <div id="output"></div>

  <script>
    document.getElementById('fetchButton').addEventListener('click', () => {
      fetch('https://c7c3-154-237-204-95.ngrok-free.app/api/example')
        .then(response => response.json())
        .then(data => {
          document.getElementById('output').innerText = data.message;
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
  </script>
</body>
</html>
