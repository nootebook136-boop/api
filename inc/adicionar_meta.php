<?php


$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $nome = trim($_GET['nome'] ?? "");
    $valor = $_GET['valor_meta'] ?? 0;
    $data = $_GET['data_limite'] ?? "";

    if ($nome == "" || $valor <= 0 || $data == "") {
        exit("Preencha os campos corretamente.");
    }

    $sql = "INSERT INTO tb_metas
(id_usuario, ds_nome, vl_atual, vl_meta, dt_limite)
VALUES (?, ?, 0, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("isis", $id, $nome, $valor, $data);
    if ($stmt->execute()) {

        $resposta = [
            "sucesso" => true,
            "mensagem" => "Meta adicionada com sucesso"
        ];

    } else {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Erro ao adicionar meta"
        ];

    }


    $stmt->close();
}




?>