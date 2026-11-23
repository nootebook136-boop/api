
<?php

$id = trim($_GET["id"] ?? "");
$transacoes = [];

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Usuário inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ? ORDER BY dt_transacao DESC, cd_transacao DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        if (
            $t["dt_transacao"] <= date("Y-m-d") &&
            count($transacoes) < 5
        ) {

            $transacoes[] = [
                "descricao" => $t["ds_transacao"],
                "tipo" => $t["ds_tipo"],
                "valor" => (float) $t["vl_transacao"],
                "data" => $t["dt_transacao"]
            ];
        }
    }

    $stmt->close();

    $resposta = [
        "sucesso" => true,
        "ultimas_transacoes" => $transacoes
    ];
}

?>
