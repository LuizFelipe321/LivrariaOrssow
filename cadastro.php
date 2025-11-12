<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $camposObrigatorios = ['nome', 'sobrenome', 'telefone', 'data_nascimento', 'email', 'senha'];
    foreach ($camposObrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            echo "<script>alert('Por favor, preencha todos os campos!'); window.history.back();</script>";
            exit;
        }
    }

    $nome = htmlspecialchars(trim($_POST['nome']));
    $sobrenome = htmlspecialchars(trim($_POST['sobrenome']));
    $telefone = htmlspecialchars(trim($_POST['telefone']));
    $data_nascimento = htmlspecialchars(trim($_POST['data_nascimento']));
    $email = htmlspecialchars(trim($_POST['email']));
    $senha = htmlspecialchars(trim($_POST['senha']));

    try {
        
        $valida = $conn->prepare("SELECT id FROM cadastro WHERE email = ?");
        $valida->bind_param("s", $email);
        $valida->execute();
        $valida->store_result();

        if ($valida->num_rows > 0) {
            echo "
            <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'>
                <title>Erro no Cadastro</title>
                <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
                <style>
                    body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #f3e9dc;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        height: 100vh;
                        margin: 0;
                        text-align: center;
                    }
                    .box {
                        background-color: #fff;
                        padding: 40px 60px;
                        border-radius: 12px;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                    }
                    h2 {
                        color: #6a4e23;
                        margin-bottom: 10px;
                    }
                    a {
                        text-decoration: none;
                        background-color: #6a4e23;
                        color: white;
                        padding: 10px 20px;
                        border-radius: 8px;
                        transition: 0.3s;
                        font-weight: 600;
                    }
                    a:hover {
                        background-color: #8b5e34;
                    }
                </style>
            </head>
            <body>
                <div class='box'>
                    <h2> E-mail já cadastrado!</h2>
                    <p>Use outro e-mail ou faça login em sua conta.</p>
                    <a href='login.html'>Ir para o Login</a>
                </div>
            </body>
            </html>";
            exit;
        }

        $valida->close();

        $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

        $sql = $conn->prepare("INSERT INTO cadastro (nome, sobrenome, telefone, data_nascimento, email, senha) 
                            VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssss", $nome, $sobrenome, $telefone, $data_nascimento, $email, $hash_senha);
        $sql->execute();

        echo "
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Cadastro Concluído</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    background-color: #f3e9dc;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                    text-align: center;
                }
                .box {
                    background-color: #fff;
                    padding: 40px 60px;
                    border-radius: 12px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                }
                h2 {
                    color: #3b2f2f;
                    margin-bottom: 15px;
                }
                a {
                    text-decoration: none;
                    background-color: #6a4e23;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 8px;
                    transition: 0.3s;
                    font-weight: 600;
                }
                a:hover {
                    background-color: #8b5e34;
                }
            </style>
        </head>
        <body>
            <div class='box'>
                <h2>🎉 Cadastro realizado com sucesso!</h2>
                <p>Bem-vindo(a) à Livraria Orsow. Faça login para começar.</p>
                <a href='login.html'>Ir para o Login</a>
            </div>
        </body>
        </html>";

        $sql->close();
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Erro ao processar o cadastro: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    } finally {
        mysqli_close($conn);
    }
}
?>
