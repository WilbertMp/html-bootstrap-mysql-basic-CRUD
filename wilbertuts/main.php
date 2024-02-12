<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .footer {
            color: black;
        }

        .nav-item {
            cursor: pointer;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                            <li><a class="dropdown-item" onclick="logout()">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container d-flex flex-column w-100 vh-100">
        <div class="container align-self-center flex-fill d-flex align-items-center vh-100">
            <div class="scrollable w-50 h-100 p-3 overflow-y-auto overflow-x-hidden d-flex flex-column align-items-center" id="itemContainer">
            </div>
            <div class="input p-5 w-50 h-100">
                <h3>Upload Item</h3>
                <form enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Nama Item:</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Item:</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga Item:</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Item:</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this);" required>
                        <img id="preview" class="mt-3" style="max-width: 300px; max-height: 300px; display: none;" alt="Preview Gambar">
                    </div>

                    <button type="button" class="btn btn-primary" onclick="uploadItem()">Unggah</button>
                </form>
            </div>
        </div>
        <?php include 'footer.php' ?>
    </div>


    <script>
        function previewImage(input) {
            var preview = document.getElementById('preview');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = 'none';
            }
        }
    </script>

    <script>
        checkSession();

        function createCard(item) {
            const card = document.createElement('div');
            card.className = 'card bg-body-secondary mb-3';
            card.style = 'max-width: 18rem;';

            const img = document.createElement('img');
            img.src = `asset/upload/${item.gambar}`;
            img.className = 'card-img-top';
            img.alt = '...';

            const cardBody = document.createElement('div');
            cardBody.className = 'card-body';

            const title = document.createElement('h5');
            title.className = 'card-title';
            title.textContent = item.judul;

            const description = document.createElement('p');
            description.className = 'card-text';
            description.textContent = item.deskripsi;
            if (item.deskripsi.length > 50) {
                description.textContent = `${item.deskripsi.substring(0,50)}...`;
            }

            const price = document.createElement('p');
            price.className = 'card-text';
            price.textContent = `Harga: Rp. ${item.harga}`;

            const detailButton = document.createElement('a');
            detailButton.href = 'detail.php?id=' + item.id;
            detailButton.className = 'btn btn-primary me-2';
            detailButton.textContent = 'Detail';

            const deleteButton = document.createElement('a');
            deleteButton.href = '#';
            deleteButton.className = 'btn btn-danger';
            deleteButton.textContent = 'Hapus';
            deleteButton.setAttribute('onclick', `deleteItem(${item.id}, '${item.gambar}')`);

            cardBody.appendChild(title);
            cardBody.appendChild(description);
            cardBody.appendChild(price);
            cardBody.appendChild(detailButton);
            cardBody.appendChild(deleteButton);

            card.appendChild(img);
            card.appendChild(cardBody);

            return card;
        }

        function uploadItem() {
            const judul = document.getElementById('judul').value;
            const deskripsi = document.getElementById('deskripsi').value;
            const harga = document.getElementById('harga').value;
            const gambarInput = document.getElementById('gambar');

            if (!judul || !deskripsi || !harga || !gambarInput.files[0]) {
                alert('Silakan lengkapi semua field pada formulir.');
                return;
            }

            const gambar = gambarInput.files[0];
            const formData = new FormData();
            formData.append("judul", judul);
            formData.append("deskripsi", deskripsi);
            formData.append("harga", harga);
            formData.append("gambar", gambar);
            formData.append("akunid", sessionStorage.getItem('akunid'));

            axios.post('https://gaskuytopupstore.000webhostapp.com/api/item/create_item.php', formData, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                    }
                })
                .then(response => {
                    if (response.data.status === 'success') {
                        fetchAndRenderData();
                        alert('Item berhasil diunggah');
                        location.reload();
                    } else {
                        alert('Gagal mengunggah item');
                    }
                })
                .catch(error => console.error('Error menambahkan item :', error));
        }

        function deleteItem(itemId, gambar) {
            const formData = new FormData();
            formData.append("id", itemId);
            formData.append("gambar", gambar);
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                axios.post('https://gaskuytopupstore.000webhostapp.com/api/item/delete_item.php', formData)
                    .then(response => {
                        if (response.data.status === 'success') {
                            alert('Item berhasil dihapus');
                            fetchAndRenderData();
                            location.reload();
                        } else {
                            alert('Gagal menghapus item');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function fetchAndRenderData() {
            var akunid = sessionStorage.getItem('akunid');
            const fd = new FormData();
            fd.append('akunid', akunid);
            axios.post('https://gaskuytopupstore.000webhostapp.com/api/item/read_item.php', fd)
                .then(response => {
                    const data = response.data.item;
                    const itemContainer = document.getElementById('itemContainer');
                    itemContainer.innerHTML = '';
                    data.forEach(item => {
                        const card = createCard(item);
                        itemContainer.appendChild(card);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function logout() {
            const session = sessionStorage.getItem("session");
            const formData = new FormData();
            formData.append("session", session);
            axios.post('https://gaskuytopupstore.000webhostapp.com/api/logout.php', formData)
                .then(response => {
                    console.log(response)
                    if (response.data.status == 'success') {
                        sessionStorage.removeItem('session');
                        sessionStorage.removeItem("nama");
                        sessionStorage.removeItem("akunid");
                        window.location.href = 'index.php';
                    } else {
                        console.error('Logout gagal, silahkan coba lagi : ', error);
                    }
                })
                .catch(error => {
                    console.error('Error saat melakukan logout: ', error);
                });
        }

        function checkSession() {
            const formData = new FormData();
            formData.append('session', sessionStorage.getItem('session'));
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
                    fetchAndRenderData();
                } else {
                    console.error('status cek session gagal : ', response.data.message);
                }
            }).catch(error => {
                console.error('error ketika cek session : ', response.data.error);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>