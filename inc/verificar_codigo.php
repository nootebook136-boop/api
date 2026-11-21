<?php

$email = trim($_GET["email"] ?? "");
$codigo = trim($_GET["codigo"] ?? "");

if ($email == " " || !preg_match("/^[0-9]{6}$/", $codigo)) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Código ou e-mail inválido."
    ];

} else {

    $sql = "SELECT * FROM tb_usuarios
            WHERE ds_email = ?
            AND ds_status = 'ativo'";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $valido = false;

    if ($usuario) {

        $id = $usuario["cd_usuario"];

        $sql = "SELECT * FROM tb_recuperacao_senha
                WHERE id_usuario = ?
                AND dt_utilizado IS NULL
                ORDER BY cd_recuperacao DESC
                LIMIT 1";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $recuperacao = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($recuperacao) {

            $agora = time();
            $expiracao = strtotime($recuperacao["dt_expiracao"]);

            if (
                $agora <= $expiracao &&
                $recuperacao["nr_tentativas"] < 5
            ) {

                $valido = password_verify(
                    $codigo,
                    $recuperacao["ds_codigo"]
                );

                if (!$valido) {

                    $tentativas = $recuperacao["nr_tentativas"] + 1;

                    $sql = "UPDATE tb_recuperacao_senha
                            SET nr_tentativas = ?
                            WHERE cd_recuperacao = ?";

                    $stmt = $conexao->prepare($sql);
                    $stmt->bind_param(
                        "ii",
                        $tentativas,
                        $recuperacao["cd_recuperacao"]
                    );
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }

    $resposta = [
        "sucesso" => $valido,
        "mensagem" => $valido
            ? "Código válido."
            : "Código inválido ou expirado."
    ];
}

?>