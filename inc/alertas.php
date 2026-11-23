
<?php

$id = trim($_GET["id"] ?? "");

$alertas = [];
$saldo = 0;
$receitas = 0;
$despesas = 0;

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Usuário inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        $valor = (float) $t["vl_transacao"];

        if ($t["dt_transacao"] <= date("Y-m-d")) {

            if ($t["ds_tipo"] == "receita") {
                $saldo += $valor;
            } else if ($t["ds_tipo"] == "despesa") {
                $saldo -= $valor;
            }
        }

        if (date("Y-m", strtotime($t["dt_transacao"])) == date("Y-m")) {

            if ($t["ds_tipo"] == "receita") {
                $receitas += $valor;
            } else if ($t["ds_tipo"] == "despesa") {
                $despesas += $valor;
            }
        }
    }

    $stmt->close();

    if ($saldo < 0) {

        $alertas[] = [
            "tipo" => "despesa",
            "mensagem" => "Seu saldo está negativo."
        ];

    } else {

        $alertas[] = [
            "tipo" => "receita",
            "mensagem" => "Seu saldo está positivo."
        ];
    }

    if ($despesas > $receitas) {

        $alertas[] = [
            "tipo" => "despesa",
            "mensagem" => "Suas despesas do mês ultrapassaram as receitas."
        ];
    }

    $resposta = [
        "sucesso" => true,
        "alertas" => $alertas
    ];
}

?>
