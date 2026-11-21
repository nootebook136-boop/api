<?php

$id =  $_GET['id'] ?? 0;
$tipo = trim($_GET['tipo_transacao'] ?? "");
$descricao = trim($_GET['descricao'] ?? "");
$valor = $_GET['valor'] ?? "";
$categoria =  $_GET['categoria'] ?? 0;
$data = trim($_GET['data'] ?? "");

if (
    $id <= 0 ||
    $tipo == "" ||
    $descricao == "" ||
    $valor == "" ||
    $categoria <= 0 ||
    $data == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} 

 else {

    
    $sql = "SELECT cd_categoria FROM tb_categorias
            WHERE cd_categoria = ?
            AND id_usuario = ?
            AND ds_tipo = ?
            AND ds_status = 'ativo'";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("iis", $categoria, $id, $tipo);
    $stmt->execute();
    $stmt->store_result();

    $categoriaValida = $stmt->num_rows > 0;
    $stmt->close();

    if (!$categoriaValida) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Categoria inválida para esta transação."
        ];

    } else {



        $sql = "INSERT INTO tb_transacoes
                    (id_usuario, id_categoria, ds_transacao,
                     vl_transacao, dt_transacao, ds_tipo)
                    VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "iisiss",
            $id,
            $categoria,
            $descricao,
            $valor,
            $data,
            $tipo
        );

        if ($stmt->execute()) {

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Transação adicionada com sucesso!"
            ];
        } else {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao adicionar transação."
            ];
        }



        $stmt->close();

    }
}

?>