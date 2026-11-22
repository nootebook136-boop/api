
<?php

$id = trim($_GET["id"] ?? "");
$idmeta = trim($_GET["id_meta"] ?? "");
$nome = trim($_GET["nome"] ?? "");
$valor = $_GET["valor_meta"] ?? "";
$data = trim($_GET["data_limite"] ?? "");

if (
    $id == "" ||
    $idmeta == "" ||
    $nome == "" ||
    $valor == "" ||
    $data == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} else if (!is_numeric($valor) || $valor <= 0) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Valor alvo inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_metas
            WHERE cd_meta = ?
            AND id_usuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $idmeta, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $meta = $resultado->fetch_assoc();

    $stmt->close();

    if (!$meta) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Meta não encontrada."
        ];

    } else {

        $sql = "UPDATE tb_metas
                SET ds_nome = ?,
                    vl_meta = ?,
                    dt_limite = ?
                WHERE cd_meta = ?
                AND id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sdsii", $nome, $valor, $data, $idmeta, $id);

        if ($stmt->execute()) {

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Meta editada com sucesso!"
            ];

        } else {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao editar meta."
            ];
        }

        $stmt->close();
    }
}

?>
