<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body {
      background: url('asset/background.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .kontener-form-signup {
      opacity: 95%;
    }
  </style>
</head>

<body>
  <div class="container d-flex flex-column w-100 vh-100">
    <?php include 'navbar.php' ?>
    <form class="form-signup align-self-center flex-fill d-flex align-items-center">
      <div class="kontener-form-signup container py-5 p-5 rounded-3 bg-body-secondary d-flex flex-column justify-content-center shadow-lg">
        <h2 class="text-center">REGISTER</h2>
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        
        <label for="inputName" class="sr-only">Name</label>
        <input type="name" id="name" name="name" class="form-control" placeholder="Your Name" required>
        
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required>


        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

        <label for="confirmPassword" class="sr-only">Confirm Password</label>
        <input type="password" id="cpassword" name="cpassword" class="form-control" placeholder="Confirm Password" required>

        <button type="button" class="btn btn-lg btn-primary btn-block mt-3" onclick="register()">Register</button>
      </div>
    </form>
    <?php include 'footer.php' ?>
  </div>
  <script>
    function register() {
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const cpassword = document.getElementById('cpassword').value;
      const email = document.getElementById('email').value;
      const name = document.getElementById('name').value;

      console.log(`username : ${username} password : ${password}`);

      const formData = new FormData();
      formData.append("username", username);
      formData.append("password", password);
      formData.append("email", email);
      formData.append("name", password);
      console.log(`username : ${username} password : ${password} email : ${email} name : ${name}`);

      if (password == cpassword) {
        axios.post('https://gaskuytopupstore.000webhostapp.com/api/register.php', formData)
          .then(response => {
            console.log(response.data.status)
            if (response.data.status == 'success') {
              console.log(response.data.status);
              window.location.href = 'login.php';
            } else {
              alert('Register gagal, mohon masukan data dengan benar');
            }
          })
          .catch(error => {
            console.error('Error checking session: ', error);
          });
      } else {
        alert('Register gagal, mohon masukan konfirmasi password dengan benar');
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>