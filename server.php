<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome"], $_POST["email"], $_POST["senha"], $_POST["sobrenome"], $_POST["telefone"], $_POST["data_nascimento"])) {
        $nome = htmlspecialchars($_POST['nome']);
        $sobrenome = htmlspecialchars($_POST['sobrenome']);
        $telefone = htmlspecialchars($_POST['telefone']);
        $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
        $email = htmlspecialchars($_POST['email']);
        $senha = htmlspecialchars($_POST['senha']);

        if (empty($nome) || empty($email) || empty($senha)) {
            echo "Por favor, preencha todos os campos.";
        } elseif (strlen($senha) < 6) {
            echo "A senha deve ter pelo menos 6 caracteres.";
        } else {
            echo "<h3>Cadastro realizado com sucesso!</h3>";
            echo "Nome: "  . $nome . "<br>";
            echo "Sobrenome: " . $sobrenome . "<br>";
            echo "Telefone: " . $telefone . "<br>";
            echo "Data de nascimento: " . $data_nascimento . "<br>";
            echo "Email: "  . $email . "<br>";
            echo "Senha: " . $senha . "<br>";
        }
    } else {
        echo "Dados não encontrados no formulário.";
    }
} else {
    echo "Nenhum dado enviado.";
}
?>