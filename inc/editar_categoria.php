
<?php

$id = trim($_GET["id"] ?? "");
$idcategoria = trim($_GET["id_categoria"] ?? "");
$nome = trim($_GET["nome"] ?? "");
$tipo = trim($_GET["tipo_categoria"] ?? "");
$cor = trim($_GET["cor"] ?? "");
$icone = trim($_GET["icone"] ?? "");

if (
    $id == "" ||
    $idcategoria == "" ||
    $nome == "" ||
    $tipo == "" ||
    $cor == "" ||
    $icone == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} 
 else {

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

        $podeEditar = true;

        if ($tipo != $categoria["ds_tipo"]) {

            $sql = "SELECT * FROM tb_transacoes
                    WHERE id_categoria = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ii", $idcategoria, $id);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $podeEditar = false;
            }

            $stmt->close();

            $sql = "SELECT * FROM tb_orcamentos
                    WHERE id_categoria = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ii", $idcategoria, $id);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $podeEditar = false;
            }

            $stmt->close();
        }

        if (!$podeEditar) {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Não é possível alterar o tipo de uma categoria que já possui movimentações."
            ];

        } else {

            $sql = "UPDATE tb_categorias
                    SET ds_nome = ?,
                        ds_tipo = ?,
                        ds_cor = ?,
                        ds_icone = ?
                    WHERE cd_categoria = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "ssssii",
                $nome,
                $tipo,
                $cor,
                $icone,
                $idcategoria,
                $id
            );

            $stmt->execute();
            $stmt->close();

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Categoria editada com sucesso!"
            ];
        }
    }
}

?>
