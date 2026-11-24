<?php

$id =$_GET["id"] ?? "";
$nome = trim($_GET["nome"] ?? "");
$email = trim($_GET["email"] ?? "");
$telefone = trim($_GET["telefone"] ?? "");
$cpf = preg_replace('/\D/', '', $_GET["cpf"] ?? "");
$nascimento = $_GET["nascimento"] ?? "";

if ($id == null) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "ID invalido"
    ];

} else if (
    $nome == "" || $email == "" || $telefone == "" ||
    $cpf == "" || $nascimento == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

} else {

    $sql = "SELECT cd_usuario FROM tb_usuarios
            WHERE ds_email = ? AND cd_usuario != ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $emailExiste = $resultado->num_rows > 0;
    $stmt->close();

    $sql = "SELECT cd_usuario FROM tb_usuarios
            WHERE ds_cpf = ? AND cd_usuario != ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $cpf, $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cpfExiste = $resultado->num_rows > 0;
    $stmt->close();

    if ($emailExiste) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "E-mail já cadastrado."
        ];

    } else if ($cpfExiste) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "CPF já cadastrado."
        ];

    } else {

        $sql = "UPDATE tb_usuarios SET
                nm_usuario = ?,
                ds_email = ?,
                ds_telefone = ?,
                ds_cpf = ?,
                dt_nascimento = ?
                WHERE cd_usuario = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sssssi",
            $nome,
            $email,
            $telefone,
            $cpf,
            $nascimento,
            $id
        );

        if ($stmt->execute()) {

            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            $_SESSION['telefone'] = $telefone;
            $_SESSION['cpf'] = $cpf;
            $_SESSION['nascimento'] = $nascimento;

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Perfil atualizado com sucesso!"
            ];

        } else {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao atualizar perfil."
            ];

        }

        $stmt->close();

    }

}