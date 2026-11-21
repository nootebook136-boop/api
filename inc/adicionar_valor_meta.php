<?php

$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

   
    $valor = $_GET['valor_meta'] ?? 0;
    $idMeta = $_GET['id_meta'] ?? "";

    if ($idMeta== "" || $valor <= 0 ) {
        exit("Preencha os campos corretamente.");
    }


    $sql = "UPDATE tb_metas
            SET vl_atual = vl_atual + ?
            WHERE cd_meta = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("iii", $valor, $idMeta, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {

        $resposta = [
            "sucesso" => true,
            "mensagem" => "Valor adicionado com sucesso."
        ];

    } else {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Meta não encontrada."
        ];

    }

    $stmt->close();
}
?>