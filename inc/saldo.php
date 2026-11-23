
<?php

$id = trim($_GET["id"] ?? "");
$saldo = 0;

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "ID  inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        if ($t["dt_transacao"] <= date("Y-m-d")) {

            if ($t["ds_tipo"] == "receita") {
                $saldo += $t["vl_transacao"];
            } else if ($t["ds_tipo"] == "despesa") {
                $saldo -= $t["vl_transacao"];
            }
        }
    }

    $stmt->close();

    $resposta = [
        "sucesso" => true,
        "saldo" => $saldo
    ];
}

?>
