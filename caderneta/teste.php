<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESTE - MedNotes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="configADM.css">
    <link rel="shortcut icon" type="image/svg" href="/logo-aba_book-medical-solid.svg"/>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Cabeçalho -->
    <header class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div class="flex items-center">
                <a href="index.html" class="mr-4 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-book-medical text-xl text-blue-500 mr-2"></i>
                <h1 class="text-lg sm:text-xl font-bold text-gray-800">MedNotes - Admin Center</h1>
            </div>
            <div class="flex items-center gap-2 self-end sm:self-auto">
                <button id="add-user-btn"class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm sm:text-base">
                    <i class="fas fa-plus mr-1 sm:mr-2"></i><span class="hidden sm:inline">Adicionar</span>
                </button>
                <button id="logout-btn" class="text-gray-600 hover:text-blue-600 text-sm sm:text-base">
                    <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline"><a href="../../login.html">Sair</a></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto p-2 sm:p-4 pb-20">
        <!-- Seção de Usuários -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">Gerenciamento de Usuários</h2>
            </div>

            <!-- Grupos de Usuários -->
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
                echo "<table class='min-w-full divide-y divide-gray-200'>
                        <tr>
                            <th class='px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>ID</th>
                            <th class='px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Email</th>
                            <th class='px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Tipo</th>
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
                                <a href='excluir_usuario.php?id=" . $row["id"] . "' class='btn btn-delete' onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\")'>Excluir</a>
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
                <!-- Os usuários serão organizados por função aqui -->
            </div>
        </div>

        <!-- Modal de Adicionar/Editar Usuário -->
        <div id="user-form-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-2 sm:p-4">
            <div class="bg-white rounded-xl p-4 sm:p-6 w-full max-w-md mx-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 id="form-modal-title" class="text-lg font-semibold">Adicionar Novo Usuário</h2>
                    <button id="close-form-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="user-form" class="space-y-3 sm:space-y-4">
                    <input type="hidden" id="user-id">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" id="name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-1 sm:py-2 px-2 sm:px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" id="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-1 sm:py-2 px-2 sm:px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                    </div>
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                        <div class="relative mt-1">
                            <input type="password" id="password" name="password" required
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 pr-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Função</label>
                        <select id="role" name="role" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-1 sm:py-2 px-2 sm:px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                            <option value="">Selecione uma função</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Professor">Professor</option>
                            <option value="Aluno">Aluno</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 sm:space-x-2">
                        <button type="button" id="cancel-form" class="px-3 py-1 sm:px-4 sm:py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm sm:text-base">
                            Cancelar
                        </button>
                        <button type="submit" class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm sm:text-base">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de Detalhes do Usuário -->
        <div id="user-details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-2 sm:p-4">
            <div class="bg-white rounded-xl p-4 sm:p-6 w-full max-w-md mx-2 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Detalhes do Usuário</h2>
                    <button id="close-user-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="user-details-content" class="space-y-2 sm:space-y-3">
                    <!-- Detalhes do usuário serão inseridos aqui -->
                </div>
                <div class="mt-4 flex flex-col sm:flex-row justify-end gap-2 sm:space-x-2">
                    <button class="px-3 py-1 sm:px-4 sm:py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm sm:text-base">
                        Editar
                    </button>
                    <button class="px-3 py-1 sm:px-4 sm:py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm sm:text-base">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé Móvel -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-around py-2 sm:py-3">
        <a href="indexADM.html" class="text-gray-600 p-2">
            <i class="fas fa-home text-lg sm:text-xl"></i>
        </a>
        <a href="configADM.html" class="text-blue-600 p-2">
            <i class="fas fa-cog text-lg sm:text-xl"></i>
        </a>
    </nav>

    <script src="configADM.js"></script>
</body>

</html>