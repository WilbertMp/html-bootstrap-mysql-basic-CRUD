<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.js"></script>
  <style>
    body {
      background: url('asset/background.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .kontener-form-signin {
        opacity: 95%;
    }
    .footer {
        color: white;
    }
  </style>
</head>

<body>
  <div class="container d-flex flex-column w-100 vh-100">
    <?php include 'navbar.php' ?>
    <form class="form-signin align-self-center flex-fill d-flex align-items-center">
      <div class="kontener-form-signin container py-5 p-5 rounded-3 bg-body-secondary d-flex flex-column justify-content-center shadow-lg">
        <h2 class="text-center">LOGIN</h2>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <button type="button" class="btn btn-lg btn-primary btn-block mt-3" onclick="login()">Login</button>
      </div>
    </form>
    <?php include 'footer.php' ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  <script>
    function login() {
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      
      console.log(`username : ${username} password : ${password}`);

      const formData = new FormData();
      formData.append("username", username);
      formData.append("password", password);

      axios.post('https://gaskuytopupstore.000webhostapp.com/api/login.php', formData)
        .then(response => {
          console.log(response.data.status)
          if (response.data.status == 'success') {
            console.log(response.data.status);
            const session = response.data.session;
            sessionStorage.setItem('session', session);
            window.location.href = 'main.php';
          } else {
            alert('Login gagal, mohon cek kembali username dan password dengan benar');
          }
        })
        .catch(error => {
          console.error('Error checking session: ', error);
        });
    }
  </script>
</body>

</html>