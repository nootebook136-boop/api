
<?php

$id = trim($_GET["id"] ?? "");
$idmeta = trim($_GET["id_meta"] ?? "");

if ($id == "" || $idmeta == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Dados inválidos."
    ];

} else {

    $sql = "SELECT * FROM tb_metas
            WHERE cd_meta = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $idmeta, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $meta = $resultado->fetch_assoc();

    $stmt->close();

    if (!$meta) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Meta não encontrada."
        ];

    } else {

        $sql = "DELETE FROM tb_metas
                WHERE cd_meta = ?
                AND id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $idmeta, $id);

        if ($stmt->execute()) {

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Meta excluída com sucesso!"
            ];

        } else {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao excluir meta."
            ];
        }

        $stmt->close();
    }
}

?>