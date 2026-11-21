<?php


    $id= trim($_GET["id"] ?? "");
   
    if ($id == "") {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Id do Usuario Invalido"
        ];

    } else {

     $sql = "SELECT *
            FROM tb_transacoes
            WHERE id_usuario = ?
            ORDER BY dt_transacao DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $resposta = [
        "sucesso" => true,
        "mensagem" => "Transações",
        "transacoes" => []
    ];

    while ($t = $resultado->fetch_assoc()) {

        $resposta["transacoes"][] = [
            "id" => $t["id_usuario"],
            "categoria" => $t["id_categoria"],
            "ds_transacao" => $t["ds_transacao"],
            "tipo" => $t["ds_tipo"],
            "vl_transacao" => $t["vl_transacao"],
            "dt_transacao" => $t["dt_transacao"]
        ];

    }

$stmt->close();
}


      
    
?>