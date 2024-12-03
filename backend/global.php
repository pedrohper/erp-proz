<?php

require_once("connection.php");
require_once("sectors/rh.php");

function checkPermission($permissionRequired){
    $permission = $_SESSION["permission"];
    return ($permission == $permissionRequired) || ($permission == "admin");
}

function login($email, $password){

    try {
        global $pdo;

        $command = "SELECT id, senha FROM funcionarios WHERE email=:email;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("email", $email);
        $cursor->execute();
        $result = $cursor->fetch(PDO::FETCH_ASSOC);

        if (empty($result)) return [false, "Usuario nao encontrado"];

        if (password_verify($password, $result["senha"])){

            $isChecked = isChecked($result["id"]);
            if ($isChecked[1]) return [true,"Success", $result["id"]];

            return [true, "Usuario nao verificado", $result["id"]];
        } else {
            return [false, "Senha incorreta"];
        }

    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function isChecked($id){

    try {
        global $pdo;

        $command = "SELECT verificado FROM funcionarios WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id",$id);

        $cursor->execute();
        $worker = $cursor->fetch(PDO::FETCH_ASSOC);

        $verify = $worker["verificado"];

        if ($verify == 0) return [true,false];
        return [true, true];

    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getAllowedPages($position_id){

    try {
        global $pdo;

        if ($position_id == 1){
            $command = "SELECT * FROM url;";
        } else {
            $command = "SELECT * FROM url WHERE id_cargo=:position_id;";
        }

        $cursor = $pdo->prepare($command);
        if ($position_id != 1) $cursor->bindParam("position_id", $position_id);
        $cursor->execute();
        $urls = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $urls];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}