<?php

$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $sql = "SELECT * FROM tb_orcamentos
            WHERE id_usuario = ?
            ORDER BY nr_ano DESC, nr_mes DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $resposta = [
        "sucesso" => true,
        "mensagem" => "orçamento",
        "orcamento" => []
    ];

    while ($o = $resultado->fetch_assoc()) {

        $idcategoria = $o["id_categoria"];

     
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

        // Buscar transações da categoria
        $sqlTransacao = "SELECT * FROM tb_transacoes
                        WHERE id_usuario = ?
                        AND id_categoria = ?";

        $stmtTransacao = $conexao->prepare($sqlTransacao);
        $stmtTransacao->bind_param("ii", $id, $idcategoria);
        $stmtTransacao->execute();

        $resultadoTransacao = $stmtTransacao->get_result();

        $gasto = 0;

        while ($t = $resultadoTransacao->fetch_assoc()) {

            $mes = (int) date("n", strtotime($t["dt_transacao"]));
            $ano = (int) date("Y", strtotime($t["dt_transacao"]));

            if (
                $t["ds_tipo"] == "despesa" &&
                $mes == $o["nr_mes"] &&
                $ano == $o["nr_ano"]
            ) {

                $gasto += (float) $t["vl_transacao"];

            }
        }

        $stmtTransacao->close();

        $resposta["orcamento"][] = [
            "id" => $o["cd_orcamento"],
            "categoria" => $nomeCategoria,
            "valor_limite" => $o["vl_limite"],
            "valor_gasto" => $gasto,
            "mes" => $o["nr_mes"],
            "ano" => $o["nr_ano"]
        ];
    }

    $stmt->close();
}

?>