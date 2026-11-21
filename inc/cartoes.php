<?php


$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $sql = "SELECT *
FROM tb_cartoes
WHERE id_usuario = ?
ORDER BY nr_dia_vencimento ASC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();


    $resposta = [
        "sucesso" => true,
        "mensagem" => "cartões",
        "cartoes" => []
    ];
    while ($c = $resultado->fetch_assoc()) {

        $resposta["cartoes"][] = [
            "id" => $c["cd_cartao"],
            "nome" => $c["ds_nome"],
            "bandeira" => $c["ds_bandeira"],
            "final" => $c["nr_final"],
            "valor_limite" => $c["vl_limite"],
            "fatura_atual" => $c["vl_fatura_atual"],
            "dia_fechamento" => $c["nr_dia_fechamento"],
            "dia_vencimento" => $c["nr_dia_vencimento"]
        ];

    }



    $stmt->close();
}




?>