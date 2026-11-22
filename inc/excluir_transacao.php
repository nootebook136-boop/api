
<?php

$id = trim($_GET["id"] ?? "");
$idtransacao = trim($_GET["id_transacao"] ?? "");

if ($id == "" || $idtransacao == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Dados inválidos."
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

        $sql = "SELECT * FROM tb_recorrencias
                WHERE id_transacao = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $idtransacao);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $temRecorrencias = $resultado->num_rows > 0;

        $stmt->close();

        if ($temRecorrencias) {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Essa transação possui recorrências e não pode ser excluída."
            ];

        } else {

            $sql = "DELETE FROM tb_transacoes
                    WHERE cd_transacao = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ii", $idtransacao, $id);

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

                $resposta = [
                    "sucesso" => true,
                    "mensagem" => "Transação excluída com sucesso!"
                ];

            } else {

                $resposta = [
                    "sucesso" => false,
                    "mensagem" => "Erro ao excluir transação."
                ];
            }

            $stmt->close();
        }
    }
}

?>
