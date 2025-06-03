<?php
session_start();

$response = ['authenticated' => false];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['authenticated'] = true;
    $response['user'] = $_SESSION['username'] ?? 'Usuário'; // opcional
}

header('Content-Type: application/json');
echo json_encode($response);
