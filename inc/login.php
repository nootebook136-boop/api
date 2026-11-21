<?php


    $email = trim($_GET["email"] ?? "");
    $senha_digitada = $_GET["senha"] ?? "";

    if ($email == "" || $senha_digitada == "") {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Preencha todos os campos."
        ];

    } else {
$sql = "SELECT *
        FROM tb_usuarios
        WHERE ds_email = ? AND ds_status = 'ativo'
        LIMIT 1";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if ($usuario && password_verify($senha_digitada, $usuario['ds_senha'])) {

    $resposta = [
        "sucesso" => true,
        "mensagem" => "Login efetuado com sucesso!",
        "usuario" => [
            "id" => $usuario['cd_usuario'],
            "nome" => $usuario['nm_usuario'],
            "email" => $usuario['ds_email'],
            "foto" => $usuario['ds_foto'],
            "conta" => $usuario['ds_conta']
        ]
    ];

} else {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "E-mail ou senha incorretos."
    ];
}


        $stmt->close();
    }
?>