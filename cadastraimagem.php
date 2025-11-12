<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Imagens | Livraria Orsow</title>
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

        img {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 6px;
            background-color: #fafafa;
            cursor: pointer;
        }

        button {
            border: none;
            border-radius: 6px;
            padding: 8px 14px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            background-color: #4caf50;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
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
        <h1>🖼️ Cadastro de Imagens - Livraria Orsow</h1>
        <a href="home.html">🏠 Início</a>
    </header>

    <main>
        <?php
        include "classes.php";
        $atuImagem = new Produto("", "", "", "");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $produto = $_POST['produto'];
            $foto = $_FILES['foto'];
            $atuImagem->cadastraImagem($id, $produto, $foto);
            echo "<script>
                alert('Imagem enviada com sucesso!');
                window.location.href = window.location.href;
</script>";
        }

        $produtos = $atuImagem->consultaProduto();

        if ($produtos && is_array($produtos) && count($produtos) > 0) {
            echo "<h2>Produtos Cadastrados</h2>";
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Tags</th>
                            <th>Imagem</th>
                            <th>Enviar Nova</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($produtos as $prod) {
                echo "<tr>
                    <form action='' method='post' enctype='multipart/form-data'>
                        <td>{$prod['id']}
                            <input type='hidden' name='id' value='{$prod['id']}'>
                        </td>
                        <td>{$prod['produto']}
                            <input type='hidden' name='produto' value='{$prod['produto']}'>
                        </td>
                        <td>{$prod['categoria']}
                            <input type='hidden' name='categoria' value='{$prod['categoria']}'>
                        </td>

                        <td>{$prod['tags']}
                        <input type='hidden' name='tags' value='{$prod['tags']}'>
                        </td>
                        <td>";
                if (!empty($prod['caminhoImagem']) && file_exists($prod['caminhoImagem'])) {
                    echo "<img src='{$prod['caminhoImagem']}' alt='Imagem do produto' width='100'>";
                } else {
                    echo "<em>Sem imagem</em>";
                }
                echo "</td>
                        <td><input type='file' name='foto' accept='image/*' required></td>
                        <td><button type='submit'>Enviar</button></td>
                    </form>
                </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<div class='no-products'>
                    <p>📦 Nenhum produto cadastrado ainda.</p>
                    <p>Adicione produtos antes de enviar imagens.</p>
                </div>";
        }
        ?>
    </main>

    <footer>
        Livraria Orsow © 2025 — Todos os direitos reservados
    </footer>

    <script>
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.createElement('img');
            preview.style.marginTop = '10px';
            preview.style.maxWidth = '100px';
            preview.style.borderRadius = '6px';
            preview.src = URL.createObjectURL(file);
            
            const td = e.target.closest('td');
            const oldPreview = td.querySelector('img.preview-temp');
            if (oldPreview) oldPreview.remove();

            preview.classList.add('preview-temp');
            td.appendChild(preview);
        }
    });
});
</script>
</body>
</html>
