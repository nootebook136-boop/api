<?php

$nome = trim($_GET["nome"] ?? "");
$cpf = preg_replace('/\D/', '', $_GET["cpf"] ?? "");
$dt_nascimento = trim($_GET["dt_nascimento"] ?? "");
$telefone = trim($_GET["telefone"] ?? "");
$email = trim($_GET["email"] ?? "");
$senha = $_GET["senha"] ?? "";
$confirmarSenha = $_GET["confirmarsenha"] ?? "";

if (
    $nome == "" || $cpf == "" || $dt_nascimento == "" ||
    $telefone == "" || $email == "" || $senha == "" ||
    $confirmarSenha == ""
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."

    ];

} else {

    $sql = "SELECT *
            FROM tb_usuarios
            WHERE ds_email = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $sql = "SELECT *
            FROM tb_usuarios
            WHERE ds_cpf = ?";

    $pesquisa = $conexao->prepare($sql);
    $pesquisa->bind_param("s", $cpf);
    $pesquisa->execute();

    $resultado_cpf = $pesquisa->get_result();

    if ($resultado->num_rows > 0) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Este e-mail já está cadastrado."
        ];

    } else if ($resultado_cpf->num_rows > 0) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Este CPF já está cadastrado."
        ];

    } else if ($senha != $confirmarSenha) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "As senhas não coincidem."
        ];

    } else {

        $senha = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tb_usuarios
                (nm_usuario, ds_cpf, ds_telefone, dt_nascimento,
                 ds_senha, ds_email, ds_status)
                VALUES (?, ?, ?, ?, ?, ?, 'ativo')";

        $cadastro = $conexao->prepare($sql);

        $cadastro->bind_param(
            "ssssss",
            $nome,
            $cpf,
            $telefone,
            $dt_nascimento,
            $senha,
            $email
        );


        if ($cadastro->execute()) {

            $resposta = [
                "sucesso" => true,
                "mensagem" => "Usuário cadastrado com sucesso!"
            ];

        } else {


            $resposta = [
                "sucesso" => false,
                "mensagem" => "Erro ao cadastrar usuário."
            ];
        }

        $cadastro->close();
    }

    $stmt->close();
    $pesquisa->close();
}

?>