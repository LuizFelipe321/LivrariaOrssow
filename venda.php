<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit;
}

include "classes.php";
$vendeProduto = new Produto("", "", "", "");
$produtos = $vendeProduto->consultaProduto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $quantidade = (int)$_POST['quantidade'];

    $produtoExiste = array_filter($produtos, fn($p) => $p['id'] == $id);
    if (empty($produtoExiste)) {
        echo "<script>alert('Produto inválido!');</script>";
        exit;
    }

    if ($quantidade < 1 || $quantidade > 10) {
        echo "<script>alert('Quantidade inválida (1 a 10 permitidos).');</script>";
        exit;
    }

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id] += $quantidade;
    } else {
        $_SESSION['carrinho'][$id] = $quantidade;
    }

    header("Location: venda.php?adicionado=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Livraria Orsow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primaria: #3b2f2f;
            --secundaria: #f3e9dc;
            --botao: #6a4e23;
            --botao-hover: #8b5e34;
            --texto: #1c1c1c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--secundaria);
            color: var(--texto);
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primaria);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        header a {
            color: #f3e9dc;
            text-decoration: none;
            font-weight: 600;
            margin-left: 15px;
            transition: color 0.3s;
        }

        header a:hover {
            color: var(--botao-hover);
        }

        .produtos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            padding: 15px;
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card h4 {
            color: var(--primaria);
            margin-bottom: 5px;
        }

        .card p {
            font-weight: bold;
            color: var(--botao-hover);
            margin-bottom: 10px;
        }

        button {
            background-color: var(--botao);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--botao-hover);
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h2>Livraria Orsow 📚</h2>
        </div>
        <nav>
            <a href="painel.php">Painel</a>
            <a href="carrinho.php">🛒 Carrinho</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main>
        <h3>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h3>
        <br>

        <?php if (isset($_GET['adicionado'])): ?>
            <script>alert('Produto adicionado ao carrinho!');</script>
        <?php endif; ?>

        <div class="produtos">
            <?php
            if ($produtos && count($produtos) > 0) {
                foreach ($produtos as $prod) {
                    $img = htmlspecialchars($prod['imagem'] ?? 'imagens/sem-imagem.jpg');
                    $nome = htmlspecialchars($prod['produto']);
                    $preco = number_format($prod['preco'], 2, ',', '.');
                    $id = (int)$prod['id'];

                    echo "
                        <div class='card'>
                            <img src='{$img}' alt='{$nome}'>
                            <h4>{$nome}</h4>
                            <p>R$ {$preco}</p>
                            <form method='post'>
                                <input type='hidden' name='id' value='{$id}'>
                                <input type='number' name='quantidade' value='1' min='1' max='10' style='width:60px; margin-bottom:8px;'>
                                <button type='submit'>Adicionar</button>
                            </form>
                        </div>
                    ";
                }
            } else {
                echo "<p>Nenhum produto disponível no momento.</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>
