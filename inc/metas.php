<?php


$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $sql = "SELECT *
FROM tb_metas
WHERE id_usuario = ?
ORDER BY dt_limite ASC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();


    $resposta = [
        "sucesso" => true,
        "mensagem" => "Metas",
        "metas" => []
    ];

    while ($m = $resultado->fetch_assoc()) {

        $resposta["metas"][] = [
            "id" => $m["cd_meta"],
            "nome" => $m["ds_nome"],
            "valor_atual" => $m["vl_atual"],
            "valor_meta" => $m["vl_meta"],
            "data_limite" => $m["dt_limite"],
            "concluido" => $m["ds_concluido"]
        ];

    }



    $stmt->close();
}




?>