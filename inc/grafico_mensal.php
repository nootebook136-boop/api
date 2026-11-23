
<?php

$id = trim($_GET["id"] ?? "");
$grafico = [];

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Usuário inválido."
    ];

} else {

    for ($i = 5; $i >= 0; $i--) {

        $mes = date("m/Y", strtotime("first day of -$i months"));

        $grafico[$mes] = [
            "mes" => $mes,
            "receitas" => 0,
            "despesas" => 0
        ];
    }

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        $mes = date("m/Y", strtotime($t["dt_transacao"]));

        if (isset($grafico[$mes])) {

            if ($t["ds_tipo"] == "receita") {
                $grafico[$mes]["receitas"] += $t["vl_transacao"];
            } else if ($t["ds_tipo"] == "despesa") {
                $grafico[$mes]["despesas"] += $t["vl_transacao"];
            }
        }
    }

    $stmt->close();

    $resposta = [
        "sucesso" => true,
        "grafico" => array_values($grafico)
    ];
}

?>
