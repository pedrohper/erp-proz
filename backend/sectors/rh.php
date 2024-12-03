<?php

require_once dirname(__DIR__ ) ."/global.php";
require_once dirname(__DIR__ ) ."/connection.php";

function createPosition($position) {
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "INSERT INTO cargos (nome) VALUES (:nome);";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome",$position);

        $cursor->execute();

        return [true, "Success"];
    } catch ( Exception $e ) {
        return [false, $e->getMessage()];
    }
}

function updatePosition($nome, $id) {
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "UPDATE cargos SET nome=:nome WHERE id=:id;";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome",$nome);
        $cursor->bindParam("id",$id);

        $cursor->execute();

        return [true, "Success"];
    } catch ( Exception $e ) {
        return [false, $e->getMessage()];
    }
}

function getPostion($id) {
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM cargos WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id",$id);
        $cursor->execute();
        $position = $cursor->fetch(PDO::FETCH_ASSOC);

        return [true, $position];
    } catch ( Exception $e ) {
        return [false, $e->getMessage()];
    }
}

function createWorker($nome, $cpf, $endereco, $telefone, $nascimento, $admissao, $salario, $email, $senha, $verificado, $id_cargo){
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $senha = password_hash($senha, PASSWORD_BCRYPT);

        $command = "INSERT INTO funcionarios (nome, cpf, endereco, telefone, nascimento, admissao, salario, email, senha, verificado, id_cargo) VALUES (:nome, :cpf, :endereco, :telefone, :nascimento, :admissao, :salario, :email, :senha, :verificado, :id_cargo);";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome",$nome);
        $cursor->bindParam("cpf",$cpf);
        $cursor->bindParam("endereco",$endereco);
        $cursor->bindParam("telefone",$telefone);
        $cursor->bindParam("nascimento",$nascimento);
        $cursor->bindParam("admissao",$admissao);
        $cursor->bindParam("salario",$salario);
        $cursor->bindParam("email",$email);
        $cursor->bindParam("senha",$senha);
        $cursor->bindParam("verificado",$verificado);
        $cursor->bindParam("id_cargo",$id_cargo);

        $cursor->execute();
        
        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function updateWorker($nome, $cpf, $endereco, $telefone, $nascimento, $admissao, $salario, $email, $id_cargo, $id){
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "UPDATE funcionarios SET nome=:nome, cpf=:cpf, endereco=:endereco, telefone=:telefone, nascimento=:nascimento, admissao=:admissao, salario=:salario, email=:email, id_cargo=:id_cargo WHERE id = :id;";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome",$nome);
        $cursor->bindParam("cpf",$cpf);
        $cursor->bindParam("endereco",$endereco);
        $cursor->bindParam("telefone",$telefone);
        $cursor->bindParam("nascimento",$nascimento);
        $cursor->bindParam("admissao",$admissao);
        $cursor->bindParam("salario",$salario);
        $cursor->bindParam("email",$email);
        $cursor->bindParam("id_cargo",$id_cargo);
        $cursor->bindParam("id",$id);

        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function setNewPassword($senha, $id){

    try {
        global $pdo;

        $senha = password_hash($senha, PASSWORD_BCRYPT);
        $checked = true;

        $command = "UPDATE funcionarios SET senha=:senha, verificado=:verificado WHERE id=:id;";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("senha",$senha);
        $cursor->bindParam("verificado",$checked);
        $cursor->bindParam("id",$id);

        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getAllWorkers(){
    if ((!checkPermission("rh")) && (!checkPermission("services")) && (!checkPermission("sales"))) return;

    try {
        global $pdo;

        $command = "SELECT * FROM funcionarios WHERE id_cargo != 1;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $workers = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $workers];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getWorker($id){
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM funcionarios WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id",$id);
        $cursor->execute();
        $worker = $cursor->fetch(PDO::FETCH_ASSOC);

        return [true, $worker];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getAllPositions(){
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM cargos;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $positions = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $positions];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getWorkersReport(){
    if (!checkPermission("rh")) return;

    try {
        global $pdo;

        $command = "SELECT f.id AS funcionario_id, f.nome AS funcionario_nome, c.nome AS cargo_nome, f.salario AS funcionario_salario, f.admissao AS funcionario_admissao FROM funcionarios f JOIN cargos c ON f.id_cargo = c.id ORDER BY f.nome ASC;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $workers = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $workers];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function deleteWorker($id) {
    if (!checkPermission("rh")) return;

    if ($id == 1) return [false, "o administrador principal não pode ser excluido"];

    try {
        global $pdo;

        $checkCommand = "SELECT COUNT(*) FROM compras WHERE id_funcionario = :id UNION ALL SELECT COUNT(*) FROM servicos_funcionarios WHERE id_funcionario = :id";
        $checkCursor = $pdo->prepare($checkCommand);
        $checkCursor->bindParam(':id', $id);
        $checkCursor->execute();
        
        $results = $checkCursor->fetchAll(PDO::FETCH_ASSOC);
        
        if ($results[0]['COUNT(*)'] > 0 || $results[1]['COUNT(*)'] > 0) {
            return [false, "erro: o item possui relações com outros setores"];
        }

        $command = "DELETE FROM funcionarios WHERE id = :id";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam(':id', $id);
        $cursor->execute();
        
        return [true, "Funcionário excluído com sucesso"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}