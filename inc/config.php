<?php

$host = getenv("DB_HOST");
$usuario = getenv("DB_USER");
$senha = getenv("DB_PASSWORD");
$banco = getenv("DB_NAME");
$porta = (int) (getenv("DB_PORT") ?: 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

for ($tentativa = 1; $tentativa <= 3; $tentativa++) {

    try {

        $conexao = mysqli_init();

        mysqli_options($conexao, MYSQLI_OPT_CONNECT_TIMEOUT, 15);

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

        break;

    } catch (mysqli_sql_exception $e) {

        error_log("Erro MySQL: " . $e->getMessage());

        if ($tentativa == 3 || !in_array($e->getCode(), [2002, 2003, 2006, 2013])) {

            http_response_code(500);

            echo json_encode([
                "sucesso" => false,
                "mensagem" => "Erro ao conectar ao banco de dados."
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        sleep(2);
    }
}

?>
