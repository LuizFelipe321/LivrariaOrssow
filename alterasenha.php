<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario_id'])) {
        echo "<script>alert('Sessão expirada! Faça login novamente.'); window.location.href='login.html';</script>";
        exit;
    }

    $senha = $_POST['senha'] ?? '';
    $id = $_SESSION['usuario_id'];

    if (empty($senha)) {
        echo "<script>alert('Por favor, digite uma nova senha.'); window.history.back();</script>";
        exit;
    }

    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = $conn->prepare("UPDATE cadastro SET senha = ? WHERE id = ?");
        $sql->bind_param('si', $hash_senha, $id);
        $sql->execute();

        if ($sql->affected_rows > 0) {
            echo "
            <html lang='pt-br'>
            <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Senha Alterada - Livraria Orsow</title>
              <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
              <style>
                body {
                  background-color: #f3e9dc;
                  font-family: 'Poppins', sans-serif;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  height: 100vh;
                  text-align: center;
                  background-image: url('imagens/bg-books.jpg');
                  background-size: cover;
                  background-position: center;
                  backdrop-filter: blur(5px);
                }
                .box {
                  background-color: rgba(255, 255, 255, 0.95);
                  border-radius: 20px;
                  padding: 40px 50px;
                  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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
                  font-weight: 600;
                  transition: background-color 0.3s;
                }
                a:hover {
                  background-color: #8b5e34;
                }
              </style>
            </head>
            <body>
              <div class='box'>
                <h2>Senha alterada com sucesso! 🔒</h2>
                <p>Você pode fazer login novamente com sua nova senha.</p>
                <br>
                <a href='login.html'>Ir para o Login</a>
              </div>
            </body>
            </html>
            ";
        } else {
            echo "<script>alert('Nenhuma alteração realizada. Tente novamente.'); window.history.back();</script>";
        }

        $sql->close();
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Ocorreu um erro ao alterar a senha: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    } finally {
        mysqli_close($conn);
    }
}
?>
