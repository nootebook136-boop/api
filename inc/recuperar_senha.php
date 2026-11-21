
<?php

$email = trim($_GET["email"] ?? "");

if ($email == "" ) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Informe um e-mail válido."
    ];

} else {

    $sql = "SELECT * FROM tb_usuarios
            WHERE ds_email = ?
            AND ds_status = 'ativo'";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    $stmt->close();

    if ($usuario) {

        $id = $usuario["cd_usuario"];

        
        $codigo = (string) random_int(100000, 999999);

        $codigoBanco = password_hash($codigo, PASSWORD_DEFAULT);

        $expiracao = date(
            "Y-m-d H:i:s",
            time() + 600
        );

  
        $sql = "UPDATE tb_recuperacao_senha
                SET dt_utilizado = NOW()
                WHERE id_usuario = ?
                AND dt_utilizado IS NULL";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

      
        $sql = "INSERT INTO tb_recuperacao_senha
                (id_usuario, ds_codigo, dt_expiracao)
                VALUES (?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "iss",
            $id,
            $codigoBanco,
            $expiracao
        );

        $stmt->execute();
        $stmt->close();

        
    }

    $resposta = [
        "sucesso" => true,
        "mensagem" => "Se o e-mail estiver cadastrado, enviaremos um código de verificação."
    ];
}

?>
