<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Painel - Livraria Orsow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3e9dc;
            margin: 0;
            padding: 0;
            color: #3b2f2f;
        }
        header {
            background-color: #6a4e23;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        h2 {
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        a {
            display: block;
            margin: 15px 0;
            text-decoration: none;
            background-color: #6a4e23;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #8b5e34;
        }
        .logout {
            background-color: #b43f3f;
        }
        .logout:hover {
            background-color: #d44d4d;
        }
    </style>
</head>
<body>

<header>
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?> 👋</h2>
</header>

<div class="container">
    <a href="cadastraproduto.php">➕ Cadastrar Produto</a>
    <a href="atualizaprodutos.php">🔄 Atualizar Produtos</a>
    <a href="cadastraimagem.php">🖼️ Cadastrar Imagens</a>
    <a href="venda.php">🛒 Ir para Loja</a>
    <a href="alterasenha.html">🔐 Alterar Senha</a>
    <a href="logout.php" class="logout">🚪 Sair</a>
</div>

</body>
</html>
