<?php
require_once("source/config.php");

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
