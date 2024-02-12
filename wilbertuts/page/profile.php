<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container">
            <a class="navbar-brand fs-6" href="main.php">
                <img src="asset/logo.png" style="width: 33px; height: 27px;" class="navbar-logo">Gaskuy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" data-bs-target="#" aria-expanded="false">
                            Selamat Datang, <span id="namaUser">Nama</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                            <li><a class="dropdown-item" onclick="logout()">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Profil
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama">
                        </div>
                        <div class="mb-3">
                            <label for="passwordLama" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="passwordLama" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordBaru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="passwordBaru">
                        </div>

                        <button class="btn btn-primary" onclick="ubahProfile()">Ubah</button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Akun</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus akun ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="deleteProfile()">Ya, Hapus Akun</button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script>
        const sessionToken = sessionStorage.getItem('session');
        checkSession();

        function setUser(user) {
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const nama = document.getElementById('nama');
            console.log(user);
            username.value = user.username;
            email.value = user.email;
            nama.value = user.nama;
        }

        function checkSession() {
            const formData = new FormData();
            formData.append('session', sessionToken);
            axios.post('https://gaskuytopupstore.000webhostapp.com/api/session.php', formData).then(response => {
                console.log(response);
                const akunid = response.data.user.id;
                const name = response.data.user.nama;

                if (response.data.status === 'success') {
                    sessionStorage.setItem('akunid', akunid);
                    sessionStorage.setItem("nama", name);
                    const namaUserElement = document.getElementById('namaUser');
                    if (namaUserElement) {
                        namaUserElement.textContent = name;
                    }
                    setUser(response.data.user);
                } else {
                    console.error('status cek session gagal : ', response.data.message);
                }
            }).catch(error => {
                console.error('error ketika cek session : ', response.data.error);
            });
        }


        function ubahProfile() {
            var username = document.getElementById('username').value;
            var name = document.getElementById('nama').value;
            var email = document.getElementById('email').value;
            var passwordLama = document.getElementById('passwordLama').value;
            var passwordBaru = document.getElementById('passwordBaru').value;

            const formData = new FormData();
            formData.append('session', sessionToken);
            formData.append('username', username);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('passwordLama', passwordLama);
            formData.append('passwordBaru', passwordBaru);
            if (passwordLama === "") {
                alert('Harap masukkan password lama');
            } else {
                axios.post('https://gaskuytopupstore.000webhostapp.com/api/user/update.php', formData).then(response => {
                    if (response.data.status === 'success') {
                        console.log('respon sukses : ', response.data.message);
                    } else {
                        console.error('respon gagal : ', response.data.message);
                    }
                }).catch(error => {
                    console.error('error ketika update user : ', response.data.message);
                });
            }
        }

        function deleteProfile() {

        }

        function logout() {

        }
    </script>
</body>

</html>