<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = "sql309.infinityfree.com";
$usuario = "if0_40241065";
$senha = "1LjgipstBY";
$banco = "if0_40241065_oi";
try {
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Erro ao conectar ao banco de dados.'. $e->getMessage());
}
?>