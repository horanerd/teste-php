<?php
// Conexão com banco de dados
function Conectar(): object
{
    try {


        $conexao = new PDO("mysql:host=" . HOST . "; dbname=" . DBSA . "", USER, PASS);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->exec("set names utf8");
    } catch (PDOException $erro) {
        echo "Erro na conexão:" . $erro->getMessage();
    }

    return $conexao;
}
