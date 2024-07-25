<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password_email = intval($_POST['password']);
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "f_evaluation";
$port = 3307; // Port MySQL

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
// Préparez la requête SQL
$stmt = $conn->prepare("SELECT * FROM Membre_Jury WHERE Email_prof = ? AND CIN_prof = ?");
$stmt->bind_param("si", $email, $password_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Connexion réussie
    $_SESSION['logged_in'] = true;
    $_SESSION['email'] = $email;
    header("Location: ../form/form.php"); // Redirigez vers la page souhaitée
    exit();
} else {
    // Échec de la connexion
    $error_message = "Email ou mot de passe incorrect";
}

$stmt->close();
$conn->close();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN_3</title>
    <script>
    function showError(message) {
        alert(message);
    }
    </script>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<?php
    if (isset($error_message)) {
        echo "<script>showError('$error_message');</script>";
    }
    ?>
    <header class="header">
        <nav class="navbar">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Contact</a>
        </nav>
        <div class="ecole">
            <p class="header-title">Ecole Supérieure de Technologie<br>
            <span>Fkih Ben Salah</span></p>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <h2 class="logo"><i class='bx bxl-firebase'></i>Bienvenue!</h2>
            <div class="text-sci">
                <h2><span>Vers notre site Web</span></h2>
                <p>D'evaluation des stages d'initiation , technique, professionnelle et les projets de fin d'étude</p>
            </div>
            <div class="socail-icons">
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a> 
            </div> 
        </div>

        <div class="body_1">
            <div class="wrap">
            <form method="POST" action="login.php">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="passwordInput"  placeholder="Password" required>
                    <i class='bx bxs-lock-alt' id="togglePassword"></i>
                </div>

        
                <button type="submit" class="btn">Login</button>
            </form>
            </div>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>