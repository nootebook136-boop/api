<?php


$id = trim($_GET["id"] ?? "");

if ($id == "") {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Id do Usuario Invalido"
    ];

} else {

    $nome = trim($_GET['nome'] ?? "");
    $bandeira = trim($_GET['bandeira'] ?? "");
    $final = trim($_GET['final'] ?? "");
    $vl_limite = trim($_GET['vl_limite'] ?? "");
    $vl_fatura = trim($_GET['vl_fatura'] ?? "");
    $dia_fechamento = trim($_GET['dia_fechamento'] ?? "");
    $dia_vencimento = trim($_GET['dia_vencimento'] ?? "");
 
    $valor = $_GET['valor_meta'] ?? 0;
    $data = $_GET['data_limite'] ?? "";

 

    $sql = "INSERT INTO  tb_cartoes
(id_usuario, ds_nome, ds_bandeira, nr_final, vl_limite,
vl_fatura_atual, nr_dia_fechamento, nr_dia_vencimento)
VALUES (?, ?, ?,?,?,?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("issiiiii", $id, $nome, $bandeira, $final, $vl_limite, $vl_fatura, $dia_fechamento, $dia_vencimento);
    if ($stmt->execute()) {

        $resposta = [
            "sucesso" => true,
            "mensagem" => "Cartao adicionada com sucesso"
        ];

    } else {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Erro ao adicionar Cartao"
        ];

    }


    $stmt->close();
}




?>