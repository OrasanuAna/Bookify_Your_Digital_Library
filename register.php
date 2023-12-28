<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este deja logat
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: home.php');
    exit;
}

// Conectarea la baza de date
require_once 'dbconnect.php';

// Definirea și inițializarea variabilelor
$utilizator = $parola = $confirm_parola = "";
$utilizator_err = $parola_err = $confirm_parola_err = "";
$registration_success = false;

// Procesarea datelor formularului la submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validarea numelui de utilizator
    if (empty(trim($_POST["utilizator"]))) {
        $utilizator_err = "Introduceți un nume de utilizator.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM utilizatori WHERE nume = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_utilizator);
            $param_utilizator = trim($_POST["utilizator"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $utilizator_err = "Acest nume de utilizator este deja luat.";
                } else {
                    $utilizator = trim($_POST["utilizator"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validarea parolei
    if (empty(trim($_POST["parola"]))) {
        $parola_err = "Introduceți o parolă.";     
    } elseif (strlen(trim($_POST["parola"])) < 6) {
        $parola_err = "Parola trebuie să aibă cel puțin 6 caractere.";
    } else {
        $parola = trim($_POST["parola"]);
    }

    // Validarea confirmării parolei
    if (empty(trim($_POST["confirm_parola"]))) {
        $confirm_parola_err = "Confirmați parola.";     
    } else {
        $confirm_parola = trim($_POST["confirm_parola"]);
        if (empty($parola_err) && ($parola != $confirm_parola)) {
            $confirm_parola_err = "Parolele nu se potrivesc.";
        }
    }

    // Verifică erorile înainte de a introduce în baza de date
    if (empty($utilizator_err) && empty($parola_err) && empty($confirm_parola_err)) {
        $sql = "INSERT INTO utilizatori (nume, parola, rol) VALUES (?, ?, 'user')";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_utilizator, $param_parola);
            $param_utilizator = $utilizator;
            $param_parola = password_hash($parola, PASSWORD_DEFAULT); // Creates a password hash

            if ($stmt->execute()) {
                $registration_success = true;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Închiderea conexiunii
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('bg_image.jpg'); /* Înlocuiește cu calea spre imaginea ta */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 150px;
        }
        .centered-form .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); /* Umbră pentru card */
        }
        .card-text, .card-title {
            text-align: center;
        }
        .form-group label {
            text-align: left;
        }
        .form-button {
            text-align: center; /* Centrarea butonului */
        }
        .alert {
            position: relative;
            top: 10px;
            width: 80%;
            margin: 0 auto;
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


    <?php
    // Afișează mesajele de eroare sau succes
    if (!empty($utilizator_err) || !empty($parola_err) || !empty($confirm_parola_err)) {
        echo '<div class="alert alert-danger text-center">' . $utilizator_err . ' ' . $parola_err . ' ' . $confirm_parola_err . '</div>';
    } elseif ($registration_success) {
        echo '<div class="alert alert-success text-center">Înregistrare reușită!</div>';
    }
    ?>

    <div class="centered-form">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Înregistrare</h5>
                <p class="card-text">Completați acest formular pentru a crea un cont.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Nume de utilizator</label>
                        <input type="text" name="utilizator" class="form-control" autofocus autocomplete="off">
                    </div>    
                    <div class="form-group">
                        <label>Parola</label>
                        <div class="input-group">
                        <input type="password" name="parola" class="form-control" id="passwordField" autocomplete="off">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirmați parola</label>
                    <div class="input-group">
                        <input type="password" name="confirm_parola" class="form-control" id="confirmPasswordField" autocomplete="off">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleConfirmPasswordVisibility()">
                                <i class="fa fa-eye" id="toggleConfirmPasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                    <div class="form-group form-button">
                        <input type="submit" class="btn btn-primary btn-block" value="Înregistrare">
                    </div>
                    <p class="text-center">Aveți deja un cont? <a href="login.php">Autentificați-vă aici</a>.</p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("passwordField");
            var passwordIcon = document.getElementById("togglePasswordIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }

        function toggleConfirmPasswordVisibility() {
            var confirmPasswordField = document.getElementById("confirmPasswordField");
            var confirmPasswordIcon = document.getElementById("toggleConfirmPasswordIcon");
            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                confirmPasswordIcon.classList.remove("fa-eye");
                confirmPasswordIcon.classList.add("fa-eye-slash");
            } else {
                confirmPasswordField.type = "password";
                confirmPasswordIcon.classList.remove("fa-eye-slash");
                confirmPasswordIcon.classList.add("fa-eye");
            }
        }
    </script>

</body>
</html>

