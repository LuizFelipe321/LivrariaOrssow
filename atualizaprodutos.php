<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Produtos | Livraria Orsow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f5f3ef;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #3b2f2f;
            color: #fff;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.5rem;
        }

        header a {
            color: #fff;
            text-decoration: none;
            background-color: #8b5e34;
            padding: 10px 18px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        header a:hover {
            background-color: #b8753a;
        }

        main {
            padding: 40px;
        }

        h2 {
            color: #3b2f2f;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            text-align: center;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0e6da;
            color: #3b2f2f;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"] {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 14px;
            width: 90%;
        }

        button {
            border: none;
            border-radius: 6px;
            padding: 8px 14px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        button[name="acao"][value="salvar"] {
            background-color: #4caf50;
        }

        button[name="acao"][value="salvar"]:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        button[name="acao"][value="apagar"] {
            background-color: #d9534f;
        }

        button[name="acao"][value="apagar"]:hover {
            background-color: #c9302c;
            transform: scale(1.05);
        }

        .no-products {
            text-align: center;
            padding: 40px;
            color: #6a4e23;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        footer {
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            background-color: #3b2f2f;
            color: #fff;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <header>
        <h1>📚 Painel Administrativo - Livraria Orsow</h1>
        <a href="home.html">🏠 Início</a>
    </header>

    <main>
        <?php
        include "classes.php";
        $atuProduto = new Produto("", "", "", "");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $acao = $_POST['acao'];

            if ($acao === 'salvar') {
                $produto = $_POST['produto'];
                $preco = $_POST['preco'];
                $quantidade = $_POST['quantidade'];
                $categoria = $_POST['categoria'];
                $autor = $_POST['autor'];
                $genero = $_POST['genero'];
                $publico_alvo = $_POST['publico_alvo'];
                $destaque = isset($_POST['destaque']) ? 1 : 0;
                $imagem = "";

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
    $pasta = "imagens_livros/";
    if (!file_exists($pasta)) mkdir($pasta, 0777, true);
    $nomeArquivo = time() . "_" . basename($_FILES['imagem']['name']);
    $destino = $pasta . $nomeArquivo;
    move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);
    $imagem = $destino;
}

                $atuProduto->setAutor($autor);
                $atuProduto->setGenero($genero);
                $atuProduto->setPublicoAlvo($publico_alvo);
                $atuProduto->setDestaque($destaque);
                $atuProduto->setImagem($imagem);
                $atuProduto->setProduto($produto);
                $atuProduto->setPreco($preco);
                $atuProduto->setQuantidade($quantidade);
                $atuProduto->setCategoria($categoria);
                $atuProduto->atualizaProduto($id);
                echo "<script>alert('Produto atualizado com sucesso!');</script>";
            } elseif ($acao === 'apagar') {
                $atuProduto->apagaProduto($id);
                echo "<script>alert('Produto removido com sucesso!');</script>";
            }
        }

        $produtos = $atuProduto->consultaProduto();

        if ($produtos && is_array($produtos) && count($produtos) > 0) {
            echo "<h2>Gerenciar Produtos</h2>";
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Categoria</th>
                            <th colspan='2'>Ações</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($produtos as $prod) {
                echo "<tr>
                    <form action='' method='post'>
                        <td>
                            {$prod['id']}
                            <input type='hidden' name='id' value='{$prod['id']}'>
                        </td>
                        <td><input type='text' name='produto' value='{$prod['produto']}'></td>
                        <td><input type='number' step='0.01' name='preco' value='{$prod['preco']}'></td>
                        <td><input type='number' name='quantidade' value='{$prod['quantidade']}'></td>
                        <td><input type='text' name='categoria' value='{$prod['categoria']}'></td>
                        <td><input type='text' name='autor' value='{$prod['autor']}' placeholder='Autor'></td>
                        <td><input type='text' name='genero' value='{$prod['genero']}' placeholder='Gênero'></td>
                        <td><input type='text' name='publico_alvo' value='{$prod['publico_alvo']}' placeholder='Público'></td>
                        <td><input type='file' name='imagem'></td>
                        <td><input type='checkbox' name='destaque' " . ($prod['destaque'] ? "checked" : "") . "> Destaque</td>
                        <td><button type='submit' name='acao' value='salvar'>Salvar</button></td>
                        <td><button type='submit' name='acao' value='apagar'>Apagar</button></td>
                    </form>
                </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<div class='no-products'>
                    <p>📦 Nenhum produto cadastrado até o momento.</p>
                    <p>Cadastre novos produtos na página de administração.</p>
                </div>";
        }
        ?>
    </main>

    <footer>
        Livraria Orsow © 2025 — Todos os direitos reservados
    </footer>
</body>
</html>
