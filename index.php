<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");

include_once("inc/config.php");

$tipo = $_GET["tipo"] ?? "";
$resposta = [];

if ($_SERVER["REQUEST_METHOD"] == "GET") {


    if ($tipo == "login") {
        include_once("inc/login.php");

    } else if ($tipo == "cadastrar") {
        include_once("inc/cadastrar.php");

    } else if ($tipo == "transacoes") {
        include_once("inc/transacoes.php");

    } else if ($tipo == "metas") {
        include_once("inc/metas.php");

    } else if ($tipo == "cartoes") {
        include_once("inc/cartoes.php");

    } else if ($tipo == "orcamentos") {
        include_once("inc/orcamentos.php");

    } else if ($tipo == "adicionar_meta") {
        include_once("inc/adicionar_meta.php");

    } else if ($tipo == "adicionar_valor_meta") {
        include_once("inc/adicionar_valor_meta.php");

    } else if ($tipo == "adicionar_cartao") {
        include_once("inc/adicionar_cartao.php");

    } else if ($tipo == "categoria") {
        include_once("inc/categoria.php");

    } else if ($tipo == "adicionar_categoria") {
        include_once("inc/adicionar_categoria.php");

    } else if ($tipo == "adicionar_transacao") {
        include_once("inc/adicionar_transacao.php");

    } else if ($tipo == "adicionar_orcamento") {

        include_once("inc/adicionar_orcamento.php");
    } else if ($tipo == "verificar_codigo") {
        include_once("inc/verificar_codigo.php");

    } else if ($tipo == "recuperar_senha") {
        include_once("inc/recuperar_senha.php");

    } else if ($tipo == "editar_categoria") {
        include_once("inc/editar_categoria.php");

    } else if ($tipo == "excluir_categoria") {
        include_once("inc/excluir_categoria.php");

    } else if ($tipo == "editar_transacao") {
        include_once("inc/editar_transacao.php");

    } else if ($tipo == "excluir_transacao") {
        include_once("inc/excluir_transacao.php");

    } else if ($tipo == "editar_meta") {
        include_once("inc/editar_meta.php");

    } else if ($tipo == "excluir_meta") {
        include_once("inc/excluir_meta.php");

    } else if ($tipo == "editar_cartao") {
        include_once("inc/editar_cartao.php");

    } else if ($tipo == "excluir_cartao") {
        include_once("inc/excluir_cartao.php");

    }
    else if ($tipo == "editar_orcamento") {
        include_once("inc/editar_orcamento.php");

    } else if ($tipo == "excluir_orcamento") {
        include_once("inc/excluir_orcamento.php");

    }

} else {



}

echo json_encode($resposta, JSON_UNESCAPED_UNICODE);


?>