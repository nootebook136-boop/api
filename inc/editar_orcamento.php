
<?php

$id = trim($_GET["id"] ?? "");
$idorcamento = trim($_GET["id_orcamento"] ?? "");
$categoria = trim($_GET["categoria"] ?? "");
$limite = $_GET["limite"] ?? "";
$mes = trim($_GET["mes"] ?? "");
$ano = trim($_GET["ano"] ?? "");

if (
    $id == "" ||
    $idorcamento == "" ||
    $categoria == "" ||
    $limite == "" ||
    $mes == "" ||
    $ano == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} else if (
    !ctype_digit($id) ||
    !ctype_digit($idorcamento) ||
    !ctype_digit($categoria) ||
    !is_numeric($limite) || $limite <= 0 ||
    !ctype_digit($mes) || $mes < 1 || $mes > 12 ||
    !ctype_digit($ano) || $ano < 2000 || $ano > 2100
) {

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

        $sql = "SELECT * FROM tb_categorias
                WHERE cd_categoria = ?
                AND id_usuario = ?
                AND ds_tipo = 'despesa'
                AND ds_status = 'ativo'";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $categoria, $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $categoriaValida = $resultado->fetch_assoc();

        $stmt->close();

        if (!$categoriaValida) {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Categoria inválida."
            ];

        } else {

            $sql = "SELECT * FROM tb_orcamentos
                    WHERE id_usuario = ?
                    AND id_categoria = ?
                    AND nr_mes = ?
                    AND nr_ano = ?
                    AND cd_orcamento != ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("iiiii", $id, $categoria, $mes, $ano, $idorcamento);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $existe = $resultado->fetch_assoc();

            $stmt->close();

            if ($existe) {

                $resposta = [
                    "sucesso" => false,
                    "mensagem" => "Já existe um orçamento para essa categoria nesse período."
                ];

            } else {

                $sql = "UPDATE tb_orcamentos SET
                        id_categoria = ?,
                        vl_limite = ?,
                        nr_mes = ?,
                        nr_ano = ?
                        WHERE cd_orcamento = ?
                        AND id_usuario = ?";

                $stmt = $conexao->prepare($sql);
                $stmt->bind_param(
                    "idiiii",
                    $categoria,
                    $limite,
                    $mes,
                    $ano,
                    $idorcamento,
                    $id
                );

                if ($stmt->execute()) {

                    $resposta = [
                        "sucesso" => true,
                        "mensagem" => "Orçamento editado com sucesso!"
                    ];

                } else {

                    $resposta = [
                        "sucesso" => false,
                        "mensagem" => "Erro ao editar orçamento."
                    ];
                }

                $stmt->close();
            }
        }
    }
}

?>
