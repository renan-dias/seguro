<?php
// Conexão com MySQL
$conn = new mysqli('localhost', 'root', '', 'demo_sql_injection');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$search = '';
$results = [];

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // Consulta VULNERÁVEL (concatenação direta)
    $sql = "SELECT * FROM users WHERE username LIKE '%$search%'";
    $res = $conn->query($sql);

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $results[] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>SQL Injection Demo - Busca de Usuários</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <h1 class="text-3xl font-bold mb-6">Busca de Usuários (Vulnerável a SQL Injection)</h1>

    <form method="GET" action="" class="mb-6">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Digite nome de usuário..." class="border border-gray-300 rounded p-2 w-64" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-2 hover:bg-blue-700">Buscar</button>
    </form>

    <table class="table-auto w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-blue-600 text-white">
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Usuário</th>
                <th class="px-4 py-2">Senha</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $user): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= $user['id'] ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($user['password']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center py-4 text-gray-500">Nenhum usuário encontrado</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p class="mt-6 text-red-600 font-semibold">
        <strong>Atenção:</strong> Este projeto é vulnerável a SQL Injection para fins de demonstração!
    </p>

</body>
</html>
