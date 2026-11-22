
<?php

$host = getenv("DB_HOST");
$usuario = getenv("DB_USER");
$senha = getenv("DB_PASSWORD");
$banco = getenv("DB_NAME");
$porta = (int) (getenv("DB_PORT") ?: 3306);

try {

    $conexao = mysqli_init();

    mysqli_options($conexao, MYSQLI_OPT_CONNECT_TIMEOUT, 30);

    mysqli_ssl_set($conexao, null, null, null, null, null);

    mysqli_real_connect(
        $conexao,
        $host,
        $usuario,
        $senha,
        $banco,
        $porta,
        null,
        MYSQLI_CLIENT_SSL
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
