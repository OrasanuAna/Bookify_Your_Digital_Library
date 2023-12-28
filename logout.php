<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Distrugerea sesiunii
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('bg_image.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .alert {
            position: relative;
            top: 10px;
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="alert alert-success" role="alert">
        Te-ai delogat cu succes! Vei fi redirecționat către pagina principală.
    </div>

    <script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 5000);
    </script>
</body>
</html>
