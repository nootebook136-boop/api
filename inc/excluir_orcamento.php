
<?php

$id = trim($_GET["id"] ?? "");
$idorcamento = trim($_GET["id_orcamento"] ?? "");

if ($id == "" || $idorcamento == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Dados inválidos."
    ];

} else {

    $sql = "SELECT * FROM tb_orcamentos
            WHERE cd_orcamento = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $idorcamento, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $orcamento = $resultado->fetch_assoc();

    $stmt->close();

    if (!$orcamento) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Orçamento não encontrado."
        ];

    } else {

        $sql = "DELETE FROM tb_orcamentos
                WHERE cd_orcamento = ?
                AND id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $idorcamento, $id);

        if ($stmt->execute()) {

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Orçamento excluído com sucesso!"
            ];

        } else {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao excluir orçamento."
            ];
        }

        $stmt->close();
    }
}

?>
