<?php
session_start();
include "config.php";

if (isset($_SESSION['usuario_id'])) {
    echo "<script>window.location.href = 'painel.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Formato de e-mail inválido.'); history.back();</script>";
    exit;
}

    try {
        $sql = $conn->prepare("SELECT id, nome, email, senha FROM cadastro WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result && $result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario'] = $usuario['email'];

                if (!empty($_POST['lembrar'])) {
                    setcookie('lembrar_email', $email, time() + 60 * 60 * 24 * 30, "/"); // cookie por 30 dias
                } else {
                    setcookie('lembrar_email', '', time() - 3600, "/");
                }

                // redireciona de forma elegante via JavaScript (para evitar headers quebrados)
                echo "<script>
                        alert('Login realizado com sucesso! Bem-vindo, {$usuario['nome']}');
                        window.location.href = 'painel.php';
                    </script>";
                exit;
            } else {
                echo "<script>alert('Senha incorreta. Tente novamente.'); history.back();</script>";
            }
        } else {
            echo "<script>alert('Email não encontrado. Verifique e tente novamente.'); history.back();</script>";
        }

    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Erro ao processar o login. Detalhes: " . addslashes($e->getMessage()) . "');</script>";
    } finally {
        $conn->close();
    }
}
?>
