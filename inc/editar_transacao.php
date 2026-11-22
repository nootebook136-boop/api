
<?php

$id = trim($_GET["id"] ?? "");
$idtransacao = trim($_GET["id_transacao"] ?? "");
$tipo = trim($_GET["tipo_transacao"] ?? "");
$descricao = trim($_GET["descricao"] ?? "");
$valor = $_GET["valor"] ?? "";
$categoria = trim($_GET["categoria"] ?? "");
$data = trim($_GET["data"] ?? "");
$formaPagamento = trim($_GET["forma_pagamento"] ?? "dinheiro");
$idcartao = $_GET["id_cartao"] ?? null;

if (
    $id == "" ||
    $idtransacao == "" ||
    $tipo == "" ||
    $descricao == "" ||
    $valor == "" ||
    $categoria == "" ||
    $data == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} else {

    $sql = "SELECT * FROM tb_transacoes
            WHERE cd_transacao = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $idtransacao, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $transacao = $resultado->fetch_assoc();

    $stmt->close();

    if (!$transacao) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Transação não encontrada."
        ];

    } else {

        $sql = "SELECT * FROM tb_categorias
                WHERE cd_categoria = ?
                AND id_usuario = ?
                AND ds_tipo = ?
                AND ds_status = 'ativo'";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iis", $categoria, $id, $tipo);
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

            if ($formaPagamento == "dinheiro") {
                $idcartao = null;
            }

            $sql = "UPDATE tb_transacoes SET
                    id_categoria = ?,
                    ds_transacao = ?,
                    vl_transacao = ?,
                    dt_transacao = ?,
                    ds_tipo = ?,
                    ds_forma_pagamento = ?,
                    id_cartao = ?
                    WHERE cd_transacao = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "isdsssiii",
                $categoria,
                $descricao,
                $valor,
                $data,
                $tipo,
                $formaPagamento,
                $idcartao,
                $idtransacao,
                $id
            );

            if ($stmt->execute()) {

                if ($transacao["ds_forma_pagamento"] == "cartao") {

                    $sqlCartao = "UPDATE tb_cartoes
                                  SET vl_fatura_atual = vl_fatura_atual - ?
                                  WHERE cd_cartao = ?
                                  AND id_usuario = ?";

                    $stmtCartao = $conexao->prepare($sqlCartao);

                    $stmtCartao->bind_param(
                        "dii",
                        $transacao["vl_transacao"],
                        $transacao["id_cartao"],
                        $id
                    );

                    $stmtCartao->execute();
                    $stmtCartao->close();
                }

                if ($formaPagamento == "cartao") {

                    $sqlCartao = "UPDATE tb_cartoes
                                  SET vl_fatura_atual = vl_fatura_atual + ?
                                  WHERE cd_cartao = ?
                                  AND id_usuario = ?";

                    $stmtCartao = $conexao->prepare($sqlCartao);
                    $stmtCartao->bind_param("dii", $valor, $idcartao, $id);
                    $stmtCartao->execute();
                    $stmtCartao->close();
                }

                $resposta = [
                    "sucesso" => true,
                    "mensagem" => "Transação editada com sucesso!"
                ];

            } else {

                $resposta = [
                    "sucesso" => false,
                    "mensagem" => "Erro ao editar transação."
                ];
            }

            $stmt->close();
        }
    }
}

?>
 