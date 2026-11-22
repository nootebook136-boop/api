
<?php

$id = trim($_GET["id"] ?? "");
$idcartao = trim($_GET["id_cartao"] ?? "");

if ($id == "" || $idcartao == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Dados inválidos."
    ];

} else {

    $sql = "SELECT * FROM tb_cartoes
            WHERE cd_cartao = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $idcartao, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $cartao = $resultado->fetch_assoc();

    $stmt->close();

    if (!$cartao) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Cartão não encontrado."
        ];

    } else {

        $sql = "SELECT * FROM tb_transacoes
                WHERE id_cartao = ?
                AND id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $idcartao, $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $temTransacoes = $resultado->num_rows > 0;

        $stmt->close();

        if ($temTransacoes) {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Esse cartão possui transações e não pode ser excluído."
            ];

        } else {

            $sql = "DELETE FROM tb_cartoes
                    WHERE cd_cartao = ?
                    AND id_usuario = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ii", $idcartao, $id);

            if ($stmt->execute()) {

                $resposta = [
                    "sucesso" => true,
                    "mensagem" => "Cartão excluído com sucesso!"
                ];

            } else {

                $resposta = [
                    "sucesso" => false,
                    "mensagem" => "Erro ao excluir cartão."
                ];
            }

            $stmt->close();
        }
    }
}

?>
