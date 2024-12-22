<!DOCTYPE html>
<html lang="id">
<head>
    <?php include "header.php"; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Scan Kartu RFID Staff SDN KarangPawulang">
    <title>Scan Kartu</title>

    <!-- Menambahkan Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Menambahkan Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Script untuk membaca kartu RFID -->
    <script type="text/javascript">
        $(document).ready(function() {
            setInterval(function(){
                $("#cekkartu").load('bacakartu.php')
            }, 1000); // Set interval pembacaan kartu RFID
        });
    </script>
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <style>
        /* Flexbox untuk memastikan footer selalu di bawah */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container-fluid {
            flex-grow: 1; /* Isi ruang yang tersisa */
        }
        footer {
            margin-top: auto; /* Menarik footer ke bawah */
        }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <!-- Isi Konten -->
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <h1>Scan Kartu RFID</h1>
                <div id="cekkartu">Silakan Tempelkan Kartu RFID Anda</div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <!-- Tambahkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
