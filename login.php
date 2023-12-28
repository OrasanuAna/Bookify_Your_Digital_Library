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

// Include conexiunea la baza de date
require_once 'dbconnect.php';

// Definirea variabilelor și inițializarea cu valori goale
$username = $password = "";
$username_err = $password_err = $login_err = "";
$login_success = false;

// Procesarea datelor formularului la submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Verifică dacă ambele câmpuri sunt completate
  if (empty($username) && empty($password)) {
      $login_err = "Introduceți numele de utilizator și parola.";
  } else {
      // Verifică dacă numele de utilizator este gol
      if (empty($username)) {
          $username_err = "Introduceți numele de utilizator.";
      }

      // Verifică dacă parola este goală
      if (empty($password)) {
          $password_err = "Introduceți parola.";
      }
  }
    
    // Verifică dacă utilizatorul este admin
    if ($username == 'admin' && $password == 'admin') {
      // Inițiază sesiunea ca admin
      $_SESSION["loggedin"] = true;
      $_SESSION["id"] = 0; // sau un ID special pentru admin
      $_SESSION["username"] = $username;
      $_SESSION["rol"] = 'admin';

      // Redirecționează către admin.php
      header("Location: admin.php");
      exit;
  }

    // Validarea credențialelor
    if (empty($username_err) && empty($password_err) ) {
        $sql = "SELECT id, nume, parola FROM utilizatori WHERE nume = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            
            // Încercarea de a executa declarația pregătită
            if ($stmt->execute()) {
                $stmt->store_result();
                
                // Verifică dacă numele de utilizator există, dacă da, verifică parola
                if ($stmt->num_rows == 1) {                    
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Parola este corectă, inițiază o nouă sesiune
                            if (session_status() == PHP_SESSION_NONE) {
                              session_start();
                            }
                            
                            // Stocarea datelor în variabilele de sesiune
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            $login_success = true;

                        } else {
                            // Parola nu este validă, afișează un mesaj de eroare generic
                            $login_err = "Nume de utilizator sau parolă invalidă.";
                        }
                    }
                } else {
                    // Numele de utilizator nu există, afișează un mesaj de eroare generic
                    $login_err = "Nume de utilizator sau parolă invalidă.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Închiderea declarației
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
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('bg_image.jpg');/* Înlocuiește cu calea spre imaginea ta */
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
    if (!empty($login_err)) {
        echo '<div class="alert alert-danger text-center">' . $login_err . '</div>';
    }
    if(!empty($username_err)) {
        echo '<div class="alert alert-danger text-center">' . $username_err . '</div>';
    }
    if(!empty($password_err)) {
        echo '<div class="alert alert-danger text-center">' . $password_err . '</div>';
    } elseif ($login_success) {
        echo '<div class="alert alert-success text-center">Logare reușită! Redirecționare...</div>';
        // Redirecționează după un scurt delay
        header("refresh:2;url=home.php");
        exit;
    }
    ?>

    <div class="centered-form">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Login</h5>
                <p class="card-text">Completați-vă credențialele pentru a vă autentifica.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Nume de utilizator</label>
                        <input type="text" name="username" class="form-control" autofocus autocomplete="off" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group">
                        <label>Parola</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="passwordField" autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group form-button">
                        <input type="submit" class="btn btn-primary btn-block" value="Login">
                    </div>
                    <p class="text-center">Nu aveți un cont? <a href="register.php">Înregistrați-vă acum</a>.</p>
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
</script>

</body>
</html>
