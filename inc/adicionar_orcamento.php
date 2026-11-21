<?php

$id = $_GET["id"] ?? 0;
$categoria = $_GET["categoria"] ?? 0;
$limite = $_GET["limite"] ?? "";
$mes = $_GET["mes"] ?? 0;
$ano = $_GET["ano"] ?? 0;

if (
    $id <= 0 ||
    $categoria <= 0 ||
    $limite == "" ||
    $mes == 0 ||
    $ano == 0
) {

    $resposta = [
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos."
    ];

}  else {

    $limite = (float) $limite;

  
    $sql = "SELECT * FROM tb_categorias
            WHERE cd_categoria = ?
            AND id_usuario = ?
            AND ds_tipo = 'despesa'
            AND ds_status = 'ativo'";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $categoria, $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
   
    $stmt->close();

    if ($resultado->num_rows <= 0) {

        $resposta = [
            "sucesso" => false,
            "mensagem" => "Categoria inválida."
        ];

    } else {

        $sql = "SELECT * FROM tb_orcamentos
                WHERE id_usuario = ?
                AND id_categoria = ?
                AND nr_mes = ?
                AND nr_ano = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iiii", $id, $categoria, $mes, $ano);
        $stmt->execute();

        $resultado = $stmt->get_result();
         $stmt->close();

        if ($resultado->num_rows > 0) {

            $resposta = [
                "sucesso" => false,
                "mensagem" => "Já existe um orçamento para essa categoria neste mês."
            ];

        } else {


            $sql = "INSERT INTO tb_orcamentos
                        (id_usuario, id_categoria, vl_limite, nr_mes, nr_ano)
                        VALUES (?, ?, ?, ?, ?)";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param(
                "iidii",
                $id,
                $categoria,
                $limite,
                $mes,
                $ano
            );

            if ($stmt->execute()) {

                $resposta = [
                    "sucesso" => true,
                    "mensagem" => "Orçamento adicionado com sucesso!"
                ];


            } else {


                $resposta = [
                    "sucesso" => false,
                    "mensagem" => "Não foi possível cadastrar o orçamento."
                ];

            }
            $stmt->close();
        }
    }
}

?>