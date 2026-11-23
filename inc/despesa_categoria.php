
<?php

$id = trim($_GET["id"] ?? "");
$categorias = [];

if ($id == "" || !ctype_digit($id)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Usuário inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_categorias WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($c = $resultado->fetch_assoc()) {

        if ($c["ds_tipo"] == "despesa") {

            $categorias[$c["cd_categoria"]] = [
                "nome" => $c["ds_nome"],
                "cor" => $c["ds_cor"],
                "valor" => 0
            ];
        }
    }

    $stmt->close();

    $sql = "SELECT * FROM tb_transacoes WHERE id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($t = $resultado->fetch_assoc()) {

        $categoria = $t["id_categoria"];

        if (
            $t["ds_tipo"] == "despesa" &&
            date("Y-m", strtotime($t["dt_transacao"])) == date("Y-m") &&
            isset($categorias[$categoria])
        ) {

            $categorias[$categoria]["valor"] += $t["vl_transacao"];
        }
    }

    $stmt->close();

    foreach ($categorias as $chave => $c) {

        if ($c["valor"] == 0) {
            unset($categorias[$chave]);
        }
    }

    $resposta = [
        "sucesso" => true,
        "categorias" => array_values($categorias)
    ];
}

?>
