<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "caderneta";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    // Prepara a consulta
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['user'] = $usuario;
        
        // Redirecionamentos absolutos a partir da raiz do site
        if ($usuario['tipo'] === 'admin') {
            header("Location: ../public/admin/configADM.html");
            exit();
        } elseif ($usuario['tipo'] === 'aluno') {
            header("Location: ../public/aluno/alunoUso.html");
            exit();
        } elseif ($usuario['tipo'] === 'professor') {
            header("Location: ../public/professor/profDoc.html");
            exit();
        } else {
            $_SESSION['login_error'] = "Tipo de usuário desconhecido";
            header("Location: ../login.html");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "E-mail ou senha incorretos!";
        header("Location: ../login.html");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>