
<?php

$id = trim($_GET["id"] ?? "");
$idcartao = trim($_GET["id_cartao"] ?? "");
$nome = trim($_GET["nome"] ?? "");
$bandeira = trim($_GET["bandeira"] ?? "");
$final = trim($_GET["final"] ?? "");
$limite = $_GET["vl_limite"] ?? "";
$fechamento = trim($_GET["dia_fechamento"] ?? "");
$vencimento = trim($_GET["dia_vencimento"] ?? "");

if (
    $id == "" ||
    $idcartao == "" ||
    $nome == "" ||
    $bandeira == "" ||
    $final == "" ||
    $limite == "" ||
    $fechamento == "" ||
    $vencimento == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} else if (
    !is_numeric($limite) || $limite <= 0 ||
    !preg_match('/^[0-9]{4}$/', $final) ||
    $fechamento < 1 || $fechamento > 31 ||
    $vencimento < 1 || $vencimento > 31
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Dados do cartão inválidos."
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

        $sql = "UPDATE tb_cartoes SET
                ds_nome = ?,
                ds_bandeira = ?,
                nr_final = ?,
                vl_limite = ?,
                nr_dia_fechamento = ?,
                nr_dia_vencimento = ?
                WHERE cd_cartao = ?
                AND id_usuario = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sssdiiii",
            $nome,
            $bandeira,
            $final,
            $limite,
            $fechamento,
            $vencimento,
            $idcartao,
            $id
        );

        if ($stmt->execute()) {

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Cartão editado com sucesso!"
            ];

        } else {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao editar cartão."
            ];
        }

        $stmt->close();
    }
}

?>
