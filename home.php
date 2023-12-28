<?php
session_start();

// Verifică dacă utilizatorul este logat
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'dbconnect.php';

// Căutarea cărților (dacă este aplicată)
$search = '';
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

// Query pentru a prelua cărțile și autorii lor
$sql = "SELECT carti.id, carti.titlu, carti.descriere, carti.link_pdf, carti.coperta, autori.nume AS autor_nume
        FROM carti
        JOIN autori_carti ON carti.id = autori_carti.carte_id
        JOIN autori ON autori_carti.autor_id = autori.id
        WHERE carti.titlu LIKE ? OR autori.nume LIKE ?";

$stmt = $conn->prepare($sql);
$searchTerm = '%' . $search . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-container .card {
            height: 100%;
        }

        .card-img-top {
            width: 100%;
            height: 550px;
            object-fit: cover;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .card-text {
            flex-grow: 1;
        }

        .card-title, .card-subtitle {
            margin-bottom: 10px;
        }
        .search-form {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .search-form .form-control,
        .search-form .btn {
            margin: 5px; /* Spațiu între input și buton */
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

    <div class="container mt-4">
        <div class="search-form">
            <form action="home.php" method="post" class="form-inline mb-4">
                <input type="text" name="search" class="form-control mr-sm-2" autofocus autocomplete="off" placeholder="Caută o carte sau autorul" value="<?php echo $search; ?>">
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Caută</button>
            </form>
         </div>

        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo $row['coperta']; ?>" class="card-img-top" alt="Coperta">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['titlu']; ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Autor: <?php echo $row['autor_nume']; ?></h6>
                                <p class="card-text"><?php echo $row['descriere']; ?></p>
                                <a href="<?php echo $row['link_pdf']; ?>" class="btn btn-primary" target="_blank">Download PDF</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="mx-auto">Nu s-au găsit cărți.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
