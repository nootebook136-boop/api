
<?php

$host = getenv("DB_HOST");
$usuario = getenv("DB_USER");
$senha = getenv("DB_PASSWORD");
$banco = getenv("DB_NAME");
$porta = (int) (getenv("DB_PORT") ?: 3306);

try {

    $conexao = new mysqli(
        $host,
        $usuario,
        $senha,
        $banco,
        $porta
    );

    $conexao->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {

    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao conectar ao banco de dados."
    ]);

    exit;
}

?>
