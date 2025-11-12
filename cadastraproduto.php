<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Produtos | Livraria Orsow</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
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
        margin-bottom: 20px;
    }

    form {
        background-color: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin-bottom: 40px;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        background-color: #fafafa;
    }

    button {
        background-color: #6a4e23;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 15px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #8b5e34;
        transform: scale(1.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

    td {
        color: #4d3e3e;
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
    <h1>📚 Cadastro de Produtos - Livraria Orsow</h1>
    <a href="home.html">🏠 Início</a>
</header>

<main>
    <h2>Adicionar Novo Produto</h2>

    <form action="" method="post">
        <label>Nome:</label>
        <input type="text" name="produto" placeholder="Digite o nome do livro" required>

        <label>Preço:</label>
        <input type="number" name="preco" step="0.01" placeholder="Ex: 59.90" required>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" step="1" placeholder="Ex: 10" required>

        <label>Categoria:</label>
        <input type="text" name="categoria" placeholder="Ex: Terror, Romance, Ficção..." required>

        <label>Descrição:</label>
<textarea name="descricao" rows="3" placeholder="Breve descrição do livro..." required></textarea>

        <label>Tags:</label>
        <input type="text" name="tags" placeholder="Ex: horror, suspense, psicológico" required>

        <label>Público-alvo:</label>
<select name="publico" required>
        <option value="">Selecione...</option>
        <option value="Infantil">Infantil</option>
        <option value="Juvenil">Juvenil</option>
        <option value="Adulto">Adulto</option>
</select>
        <button type="submit">Cadastrar Produto</button>
    </form>

    <?php
    include "classes.php";

    $cadProduto = new Produto();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $produto = $_POST['produto'];
        $preco = $_POST['preco'];
        $quantidade = $_POST['quantidade'];
        $categoria = $_POST['categoria'];
        $descricao = $_POST['descricao'];
        $tags = $_POST['tags'];
        $publico = $_POST['publico'];

        $cadProduto->setProduto($produto);
        $cadProduto->setPreco($preco);
        $cadProduto->setQuantidade($quantidade);
        $cadProduto->setCategoria($categoria);
        $cadProduto->setDescricao($descricao);
        $cadProduto->setTags($tags);
        $cadProduto->setPublico($publico);
        $cadProduto->insereProduto();

        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.reload();</script>";
    }

    $produtos = $cadProduto->consultaProduto();

    if ($produtos && is_array($produtos) && count($produtos) > 0) {
        echo "<h2>Produtos Cadastrados</h2>";
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Categoria</th>
                        <th>Público</th>
                        <th>Tags</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($produtos as $prod) {
            echo "<tr>
                    <td>{$prod['id']}</td>
                    <td>{$prod['produto']}</td>
                    <td>R$ {$prod['preco']}</td>
                    <td>{$prod['quantidade']}</td>
                    <td>{$prod['categoria']}</td>
                    <td>{$prod['publico']}</td>
                    <td>{$prod['tags']}</td>
                    <td>{$prod['descricao']}</td>
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<div class='no-products'>
                <p>📦 Nenhum produto cadastrado ainda.</p>
                <p>Adicione livros para começar o catálogo da livraria!</p>
            </div>";
    }
    ?>
</main>

<footer>
    Livraria Orsow © 2025 — Todos os direitos reservados
</footer>
</body>
</html>
