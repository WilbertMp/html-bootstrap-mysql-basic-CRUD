<?php
session_start();

if (empty($_SESSION['login'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container d-flex flex-column w-100 vh-100">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand fs-6" href="main.php">
                    <img src="asset/logo.png" style="width: 33px; height: 27px;" class="navbar-logo">Gaskuy
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="config/logout_user.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container align-self-center flex-fill d-flex align-items-center vh-100">
            <div class="scrollable w-50 h-100 p-3 overflow-y-auto overflow-x-hidden d-flex flex-column align-items-center" id="itemContainer">
            </div>
            <div class="input p-5 w-50 h-100">
                <h3>Upload Item</h3>
                <form action="config/item/insert_item.php" method="post" enctype="multipart/form-data">
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
                        <img id="preview" class="mt-3" style="max-width: 500px; max-height: 500px; display: none;" alt="Preview Gambar">
                    </div>

                    <button type="submit" class="btn btn-primary">Unggah</button>
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
        fetch('config/item/data_item.php')
            .then(response => response.json())
            .then(data => {
                const itemContainer = document.getElementById('itemContainer');
                data.forEach(item => {
                    const card = createCard(item);
                    itemContainer.appendChild(card);
                });
            })
            .catch(error => console.error('Error:', error));

        function createCard(item) {
            const card = document.createElement('div');
            card.className = 'card bg-body-secondary mb-3'; // Add margin-bottom for spacing
            card.style = 'max-width: 18rem;';

            const img = document.createElement('img');
            img.src = `image/${item.gambar}`;
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

            const price = document.createElement('p');
            price.className = 'card-text';
            price.textContent = `Harga: $${item.harga}`;

            const detailButton = document.createElement('a');
            detailButton.href = 'detail.php?id=' + item.id;
            detailButton.className = 'btn btn-primary me-2';
            detailButton.textContent = 'Detail';

            const deleteButton = document.createElement('a');
            deleteButton.href = '#';
            deleteButton.className = 'btn btn-danger';
            deleteButton.textContent = 'Hapus';
            deleteButton.setAttribute('onclick', `deleteItem(${item.id})`);

            cardBody.appendChild(title);
            cardBody.appendChild(description);
            cardBody.appendChild(price);
            cardBody.appendChild(detailButton);
            cardBody.appendChild(deleteButton);

            card.appendChild(img);
            card.appendChild(cardBody);

            return card;
        }

        function deleteItem(itemId) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                fetch('config/item/delete_item.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: itemId,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Fetch and render updated data
                            fetchAndRenderData();
                        } else {
                            alert('Gagal menghapus item.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function fetchAndRenderData() {
            fetch('config/item/data_item.php')
                .then(response => response.json())
                .then(data => {
                    const itemContainer = document.getElementById('itemContainer');
                    // Clear existing items
                    itemContainer.innerHTML = '';
                    // Render the updated data
                    data.forEach(item => {
                        const card = createCard(item);
                        itemContainer.appendChild(card);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>