
<?php

$id = trim($_GET["id"] ?? "");
$contas = [];

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Usuário inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ? ORDER BY dt_transacao ASC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        if (
            $t["ds_tipo"] == "despesa" &&
            $t["dt_transacao"] > date("Y-m-d") &&
            count($contas) < 5
        ) {

            $contas[] = [
                "descricao" => $t["ds_transacao"],
                "valor" => (float) $t["vl_transacao"],
                "data" => $t["dt_transacao"]
            ];
        }
    }

    $stmt->close();

    $resposta = [
        "sucesso" => true,
        "proximas_contas" => $contas
    ];
}

?>
