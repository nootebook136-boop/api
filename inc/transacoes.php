
<?php

$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $sql = "SELECT *
            FROM tb_transacoes
            WHERE id_usuario = ?
            ORDER BY dt_transacao DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $resposta = [
        "sucesso" => true,
        "mensagem" => "Transações",
        "transacoes" => []
    ];

    while ($t = $resultado->fetch_assoc()) {

        $idcategoria = $t["id_categoria"];

        $sqlCategoria = "SELECT * FROM tb_categorias
                         WHERE id_usuario = ?
                         AND cd_categoria = ?";

        $stmtCategoria = $conexao->prepare($sqlCategoria);
        $stmtCategoria->bind_param("ii", $id, $idcategoria);
        $stmtCategoria->execute();

        $resultadoCategoria = $stmtCategoria->get_result();
        $categoria = $resultadoCategoria->fetch_assoc();

        $nomeCategoria = $categoria["ds_nome"] ?? "Sem categoria";

        $stmtCategoria->close();


        $formaPagamento = $t["ds_forma_pagamento"];
        $idcartao = $t["id_cartao"];

        $nomeCartao = "";
        $finalCartao = "";

        if ($formaPagamento == "cartao") {

            $sqlCartao = "SELECT * FROM tb_cartoes
                          WHERE id_usuario = ?
                          AND cd_cartao = ?";

            $stmtCartao = $conexao->prepare($sqlCartao);
            $stmtCartao->bind_param("ii", $id, $idcartao);
            $stmtCartao->execute();

            $resultadoCartao = $stmtCartao->get_result();
            $cartao = $resultadoCartao->fetch_assoc();

            $nomeCartao = $cartao["ds_nome"] ?? "";
            $finalCartao = $cartao["nr_final"] ?? "";

            $stmtCartao->close();
        }

$resposta["transacoes"][] = [
    "id" => $t["cd_transacao"],
    "id_transacao" => $t["cd_transacao"],
    "id_usuario" => $t["id_usuario"],
    "id_categoria" => $t["id_categoria"],
    "id_cartao" => $t["id_cartao"],
    "categoria" => $nomeCategoria,
    "ds_transacao" => $t["ds_transacao"],
    "tipo" => $t["ds_tipo"],
    "vl_transacao" => $t["vl_transacao"],
    "dt_transacao" => $t["dt_transacao"],
    "forma_pagamento" => $formaPagamento,
    "nome_cartao" => $nomeCartao,
    "final_cartao" => $finalCartao
];
    }

    $stmt->close();
}

?>
