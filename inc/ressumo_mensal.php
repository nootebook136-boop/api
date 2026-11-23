
<?php

$id = trim($_GET["id"] ?? "");

$receitas = 0;
$despesas = 0;

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Usuário inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        if (date("Y-m", strtotime($t["dt_transacao"])) == date("Y-m")) {

            if ($t["ds_tipo"] == "receita") {
                $receitas += $t["vl_transacao"];
            } else if ($t["ds_tipo"] == "despesa") {
                $despesas += $t["vl_transacao"];
            }
        }
    }

    $stmt->close();

    $resposta = [
        "sucesso" => true,
        "receitas" => $receitas,
        "despesas" => $despesas
    ];
}

?>
