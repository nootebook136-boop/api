
<?php

$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {
    $sql = "SELECT * FROM tb_categorias
            WHERE id_usuario = ?
            ORDER BY cd_categoria DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $sql2 = "SELECT * FROM tb_transacoes
             WHERE id_usuario = ?";

    $stmt2 = $conexao->prepare($sql2);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $resultado2 = $stmt2->get_result();

    $transacoes = [];

    while ($t = $resultado2->fetch_assoc()) {
        $transacoes[] = $t;
    }

    $resposta = [
        "sucesso" => true,
        "mensagem" => "categoria",
        "categorias" => []
    ];

    while ($ca = $resultado->fetch_assoc()) {

        $valor_total = 0;
        $quantidade_transacoes = 0;

        foreach ($transacoes as $t) {

            if (
                $t["id_categoria"] == $ca["cd_categoria"] &&
                date("Y-m", strtotime($t["dt_transacao"])) == date("Y-m")
            ) {

                $valor_total += (float) $t["vl_transacao"];
                $quantidade_transacoes++;
            }
        }

        $resposta["categorias"][] = [
            "id" => $ca["cd_categoria"],
            "nome" => $ca["ds_nome"],
            "ds_tipo" => $ca["ds_tipo"],
            "ds_status" => $ca["ds_status"],
            "cor" => $ca["ds_cor"],
            "icone" => $ca["ds_icone"],
            "valor_total" => $valor_total,
            "quantidade_transacoes" => $quantidade_transacoes
        ];
    }

    $stmt->close();
    $stmt2->close();
}

?>
