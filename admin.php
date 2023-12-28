<?php
session_start();

// Verifică dacă utilizatorul este logat și este admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'dbconnect.php';

// ... [Restul codului de la începutul fișierului]

// Logica pentru ștergerea unei cărți
if (isset($_GET['delete'])) {
  $idToDelete = $_GET['delete'];

  // Șterge legăturile dintre cartea selectată și autorii săi
  $sqlDeleteLegaturi = "DELETE FROM autori_carti WHERE carte_id = ?";
  $stmtDeleteLegaturi = $conn->prepare($sqlDeleteLegaturi);
  $stmtDeleteLegaturi->bind_param("i", $idToDelete);
  $stmtDeleteLegaturi->execute();
  $stmtDeleteLegaturi->close();

  // Șterge cartea
  $sqlDeleteCarte = "DELETE FROM carti WHERE id = ?";
  $stmtDeleteCarte = $conn->prepare($sqlDeleteCarte);
  $stmtDeleteCarte->bind_param("i", $idToDelete);
  $stmtDeleteCarte->execute();
  $stmtDeleteCarte->close();
}

// ... [Restul codului pentru afișarea și adăugarea cărților]

// ... [Codul HTML al paginii]


// Logica pentru adăugarea unei cărți
if (isset($_POST['add'])) {
    $titlu = $_POST['titlu'];
    $descriere = $_POST['descriere'];
    $link_pdf = $_POST['link_pdf'];
    $coperta = $_POST['coperta'];
    $autor_nume = $_POST['autor_nume']; 

    $sqlAutor = "INSERT INTO autori (nume) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
    $stmtAutor = $conn->prepare($sqlAutor);
    $stmtAutor->bind_param("s", $autor_nume);
    $stmtAutor->execute();
    $autorId = $stmtAutor->insert_id;

    $sqlCarte = "INSERT INTO carti (titlu, descriere, link_pdf, coperta) VALUES (?, ?, ?, ?)";
    $stmtCarte = $conn->prepare($sqlCarte);
    $stmtCarte->bind_param("ssss", $titlu, $descriere, $link_pdf, $coperta);
    $stmtCarte->execute();
    $carteId = $stmtCarte->insert_id;

    $sqlLegatura = "INSERT INTO autori_carti (autor_id, carte_id) VALUES (?, ?)";
    $stmtLegatura = $conn->prepare($sqlLegatura);
    $stmtLegatura->bind_param("ii", $autorId, $carteId);
    $stmtLegatura->execute();

    $stmtAutor->close();
    $stmtCarte->close();
    $stmtLegatura->close();
}

// Logica pentru ștergerea unei cărți
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $sqlDelete = "DELETE FROM carti WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idToDelete);
    $stmtDelete->execute();
    $stmtDelete->close();
}

// Obține lista de cărți
$sql = "SELECT carti.id, carti.titlu, autori.nume as autor_nume FROM carti 
        JOIN autori_carti ON carti.id = autori_carti.carte_id 
        JOIN autori ON autori_carti.autor_id = autori.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panou Administrare</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">
        <img src="logo_admin.png" alt="Logo Admin Librarie" style="height: 70px;"> <!-- Ajustează înălțimea după necesități -->
    </a>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
        </li>
    </ul>
</nav>



    <div class="container mt-4">
        <h2>Panou de Administrare</h2>

        <!-- Formular pentru adăugarea unei cărți -->
        <form action="admin.php" method="post">
            <div class="form-group">
                <label for="titlu">Titlu</label>
                <input type="text" class="form-control" id="titlu" name="titlu" required>
            </div>
            <div class="form-group">
                <label for="descriere">Descriere</label>
                <textarea class="form-control" id="descriere" name="descriere" required></textarea>
            </div>
            <div class="form-group">
                <label for="link_pdf">Link PDF</label>
                <input type="text" class="form-control" id="link_pdf" name="link_pdf" required>
            </div>
            <div class="form-group">
                <label for="coperta">Link Copertă</label>
                <input type="text" class="form-control" id="coperta" name="coperta" required>
            </div>
            <div class="form-group">
                <label for="autor_nume">Nume Autor</label>
                <input type="text" class="form-control" id="autor_nume" name="autor_nume" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Adaugă Cartea</button>
        </form>

        <!-- Listarea cărților -->
        <h3 class="mt-4">Listă de Cărți</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Autor</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['titlu']; ?></td>
                        <td><?php echo $row['autor_nume']; ?></td>
                        <td>
                            <a href="admin.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Șterge</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
