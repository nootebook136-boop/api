
<?php

$id = trim($_GET["id"] ?? "");
$idcategoria = trim($_GET["id_categoria"] ?? "");

if ($id == "" || $idcategoria == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Dados inválidos."
    ];

} else {

    $sql = "SELECT * FROM tb_categorias
            WHERE cd_categoria = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $idcategoria, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $categoria = $resultado->fetch_assoc();

    $stmt->close();

    if (!$categoria) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Categoria não encontrada."
        ];

    } else {

        $sql = "SELECT * FROM tb_transacoes
                WHERE id_categoria = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $idcategoria);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $temTransacoes = $resultado->num_rows > 0;

        $stmt->close();

        $sql = "SELECT * FROM tb_orcamentos
                WHERE id_categoria = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $idcategoria);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $temOrcamentos = $resultado->num_rows > 0;

        $stmt->close();

        if ($temTransacoes || $temOrcamentos) {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Essa categoria possui transações ou orçamentos e não pode ser excluída."
            ];

        } else {

            $sql = "DELETE FROM tb_categorias
                    WHERE cd_categoria = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ii", $idcategoria, $id);
            $stmt->execute();

            $stmt->close();

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Categoria excluída com sucesso!"
            ];
        }
    }
}

?>
