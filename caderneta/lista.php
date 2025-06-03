<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .admin {
            color: #d9534f;
            font-weight: bold;
        }
        .professor {
            color: #5bc0de;
        }
        .aluno {
            color: #5cb85c;
        }
        .actions {
            text-align: center;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #337ab7;
        }
        .btn-delete {
            background-color: #d9534f;
        }
        .btn-add {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #5cb85c;
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Usuários</h1>
        
        <a href="adicionar_usuario.php" class="btn btn-add">Adicionar Novo Usuário</a>
        
        <?php
        // Configurações do banco de dados
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "caderneta";
        
        try {
            // Criar conexão
            $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Consulta SQL
            $sql = "SELECT id, email, tipo FROM usuarios ORDER BY id ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
            // Verificar se há resultados
            if ($stmt->rowCount() > 0) {
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th class='actions'>Ações</th>
                        </tr>";
                    
                // Saída de cada linha
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tipoClass = strtolower($row["tipo"]);
                    echo "<tr>
                            <td>" . htmlspecialchars($row["id"]) . "</td>
                            <td>" . htmlspecialchars($row["email"]) . "</td>
                            <td class='" . $tipoClass . "'>" . htmlspecialchars($row["tipo"]) . "</td>
                            <td class='actions'>
                                <a href='editar_usuario.php?id=" . $row["id"] . "' class='btn btn-edit'>Editar</a>
                                <a href='excluir_usuario.php?id=" . $row["id"] . "' class='delete-user action-button flex items-center text-red-600 hover:text-red-900 px-2 py-1 rounded border border-red-200 bg-red-50' data-id='" . $row["id"] . "' onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\")'>Excluir</a>"
                            </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='text-align: center; color: #666;'>Nenhum usuário cadastrado no sistema.</p>";
            }
        } catch(PDOException $e) {
            echo "<div style='color: #d9534f; background-color: #fdf7f7; padding: 15px; border-radius: 4px; border: 1px solid #ebccd1;'>
                    <strong>Erro na conexão:</strong> " . $e->getMessage() . "
                  </div>";
        }
        
        // Fechar conexão
        $conn = null;
        ?>
    </div>
</body>
</html>