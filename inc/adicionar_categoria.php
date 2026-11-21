<?php


$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $nome = trim($_GET['nome'] ?? "");
     
    $tipo = $_GET['tipo_categoria'] ?? "";
    $cor = $_GET['cor'] ?? "";
       $icone = $_GET['icone'] ?? "";
$status="ativo";
    $sql = "INSERT INTO tb_categorias
(id_usuario, ds_nome, ds_tipo,ds_cor,ds_icone,ds_status)
VALUES (?, ?, ?, ?, ?,?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("isssss", $id, $nome, $tipo, $cor,$icone,$status);
    if ($stmt->execute()) {

        $resposta = [
            "sucesso" => true,
            "mensagem" => "categoria adicionada com sucesso"
        ];

    } else {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Erro ao adicionar categoria"
        ];

    }


    $stmt->close();
}




?>