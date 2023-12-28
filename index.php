<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Librăria PDF</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('bg_image.jpg'); /* Înlocuiește cu calea spre imaginea ta */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .vertical-center {
            display: flex;
            align-items: center;
        }
        .card {
            margin-top: 150px; /* Ajustează astfel încât să fie sub navbar */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">
            <img src="logo.png" alt="Logo Librarie" style="height: 70px;"> <!-- Ajustează înălțimea după necesități -->
        </a>

        <!-- Butonul Hamburger pentru ecranele mici -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Elemente Navbar -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav mr-auto my-auto">
                <a class="nav-link" href="home.php">Download a PDF</a>
            </div>
            <div class="navbar-nav ml-auto my-auto">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="register.php">Register</a>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>



    <!-- Conținutul principal al paginii -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 vertical-center">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bine ai venit la Librăria PDF!</h5>
                        <p class="card-text">Aici poți găsi o varietate mare de cărți disponibile pentru descărcare în format PDF.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript (Opțional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
