<?php

// Configuração com banco de dados
define("HOST", "localhost");
define("DBSA", "testephp");
define("USER", "root");
define("PASS", "");


/*****
 * 
 * Funções
 * 
 */


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

// Função para calcular a idade
function calcularIdade($data)
{
    $idade = 0;
    $data_nascimento = date('Y-m-d', strtotime($data));
    list($anoNasc, $mesNasc, $diaNasc) = explode('-', $data_nascimento);

    $idade      = date("Y") - $anoNasc;
    if (date("m") < $mesNasc) {
        $idade -= 1;
    } elseif ((date("m") == $mesNasc) && (date("d") <= $diaNasc)) {
        $idade -= 1;
    }

    return $idade;
}

// Função de retorno de datas
function date_fmt(?string $date, string $format = "d/m/Y"): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format($format);
}

/***
 * 
 * Recuperar dados baseado json
 * 
 */

$pdo = Conectar();
$users = $pdo->prepare("SELECT * FROM users");
$hobbyvsuser = $pdo->prepare("SELECT u.name, h.hobbie FROM hobbies_x_users hs JOIN users as u ON u.id = hs.idUser JOIN hobbies as h ON h.id = hs.idHobby");

$pais = "Brasil";
$users = $pdo->prepare("SELECT * FROM users where country = ?");
$users->bindParam(1, $pais);
$users->execute();

$lista = array();
while ($u = $users->fetch(PDO::FETCH_OBJ)) {
    $id = $u->id;
    $name = utf8_encode($u->name);
    $country = $u->country;
    $age  = calcularIdade($u->birthDate);
    $created_at = date_fmt($u->created_at);



    $hobbyvsuser = $pdo->prepare("SELECT u.name, h.hobbie FROM hobbies_x_users hs JOIN users as u ON u.id = hs.idUser JOIN hobbies as h ON h.id = hs.idHobby WHERE u.id = {$u->id}");
    $hobbies = array();
    if ($hobbyvsuser->execute()) {
        while ($hb = $hobbyvsuser->fetch(PDO::FETCH_OBJ)) {
            $h = utf8_encode($hb->hobbie);
            $hobbies[] = $h;
        }
    }
    $dados = ["id" => $id, "name" => $name, "country" => $country, "age" => $age, "created_at" => $created_at, "hobbie" => $hobbies];
    echo json_encode($dados);
}
