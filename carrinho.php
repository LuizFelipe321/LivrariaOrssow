<?php
session_start();
include "classes.php";

$vendeProduto = new Produto("", "", "", "");
$produtos = $vendeProduto->consultaProduto();
$carrinho = $_SESSION['carrinho'] ?? [];

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>
            alert('Você precisa estar logado para acessar o carrinho!');
            window.location.href = 'login.html';
        </script>";
    exit;
}

if (isset($_POST['finalizar'])) {
    foreach ($carrinho as $id => $qtd) {
        $vendeProduto->vendeProduto($id, $qtd);
    }
    echo "<script>alert('Compra realizada com sucesso!'); window.location.href='venda.php';</script>";
    $_SESSION['carrinho'] = [];
    exit;

    $id_usuario = $_SESSION['usuario_id'];
$data = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO vendas (id_usuario, id_produto, quantidade, data_venda) VALUES (?, ?, ?, ?)");

foreach ($carrinho as $id => $qtd) {
    $stmt->bind_param("iiis", $id_usuario, $id, $qtd, $data);
    $stmt->execute();
}

$stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Livraria Orsow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3e9dc;
            margin: 0;
            padding: 40px;
            color: #3b2f2f;
        }
        header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        header a {
            text-decoration: none;
            color: #3b2f2f;
            font-size: 24px;
            margin-right: 15px;
            transition: 0.3s;
        }
        header a:hover {
            color: #8b5e34;
        }
        h2 {
            text-align: center;
            color: #6a4e23;
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #e0c9a6;
            color: #3b2f2f;
        }
        tr:last-child td {
            border-bottom: none;
        }
        img {
            border-radius: 8px;
            width: 60px;
            height: auto;
        }
        .total {
            font-weight: 600;
            background-color: #f8e7d0;
        }
        .empty {
            text-align: center;
            font-size: 18px;
            margin-top: 50px;
            color: #6a4e23;
        }
        .buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 15px;
        }
        button, .btn-link {
            background-color: #6a4e23;
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }
        button:hover, .btn-link:hover {
            background-color: #8b5e34;
        }
    </style>
</head>
<body>

<header>
    <a href="index.html">🏠</a>
    <h2>Carrinho de Compras</h2>
    <a href="logout.php" style="margin-left:auto; font-size:16px; background:#6a4e23; color:#fff; padding:6px 12px; border-radius:8px; text-decoration:none;">Sair</a>
</header>

    <?php if (empty($carrinho)): ?>
        <p class="empty">Seu carrinho está vazio.</p>
        <div class="buttons">
            <a href="venda.php" class="btn-link">🛍️ Continuar Comprando</a>
        </div>
    <?php else: ?>
        <table>
            <tr>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Total</th>
            </tr>
            <?php
            $totalGeral = 0;
            foreach ($carrinho as $id => $qtd) {
                foreach ($produtos as $prod) {
                    if ($prod['id'] == $id) {
                        $total = $prod['preco'] * $qtd;
                        $totalGeral += $total;
                        echo "
                        <tr>
                            <td><img src='{$prod['caminhoImagem']}' alt='Produto'></td>
                            <td>{$prod['produto']}</td>
                            <td>R$ " . number_format($prod['preco'], 2, ',', '.') . "</td>
                            <td>{$qtd}</td>
                            <td>R$ " . number_format($total, 2, ',', '.') . "</td>
                        </tr>";
                    }
                }
            }
            ?>
            <tr class="total">
                <td colspan="4">Total Geral</td>
                <td><strong>R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong></td>
            </tr>
        </table>

        <div class="buttons">
            <form method="post">
                <button type="submit" name="finalizar">✅ Finalizar Compra</button>
            </form>
            <a href="venda.php" class="btn-link">🛍️ Continuar Comprando</a>
        </div>
    <?php endif; ?>

</body>
</html>
