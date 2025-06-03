<?php
session_start();

// Destroi todas as variáveis de sessão
$_SESSION = array();

// Se deseja destruir o cookie de sessão também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroi a sessão
session_destroy();

// Retorna resposta JSON
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>