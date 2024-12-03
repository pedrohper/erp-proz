<?php

require_once dirname(__DIR__ ) ."/global.php";
require_once dirname(__DIR__ ) ."/connection.php";

function registerService($name, $description, $price){
    if (!checkPermission("services")) return;

    try {
        global $pdo;

        $command = "INSERT INTO servicos (nome, descricao, preco) VALUES (:nome, :descricao, :preco);";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome", $name);
        $cursor->bindParam("descricao", $description);
        $cursor->bindParam("preco", $price);

        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function updateService($name, $description, $price, $id){
    if (!checkPermission("services")) return;

    try {
        global $pdo;

        $command = "UPDATE servicos SET nome=:nome, descricao=:descricao, preco=:preco WHERE id=:id;";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome", $name);
        $cursor->bindParam("descricao", $description);
        $cursor->bindParam("preco", $price);
        $cursor->bindParam("id", $id);

        $cursor->execute();

        return [true,"Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getAllServices(){
    if (!checkPermission("services")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM servicos;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $services = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $services];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getService($id){
    if (!checkPermission("services")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM servicos WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id", $id);
        $cursor->execute();
        $service = $cursor->fetch(PDO::FETCH_ASSOC);

        return [true, $service];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getServicesReport(){
    if (!checkPermission("services")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM servicos";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $services = $cursor->fetchAll(PDO::FETCH_ASSOC);
        
        return [true, $services];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function relateWorkerToAService($id_worker, $id_service){
    if (!checkPermission("services")) return;
 
    try {
        global $pdo;

        $checkCommand = "SELECT COUNT(*) FROM servicos_funcionarios WHERE id_funcionario = :id_funcionario AND id_servico = :id_servico";
        $checkCursor = $pdo->prepare($checkCommand);
        $checkCursor->bindParam("id_funcionario", $id_worker);
        $checkCursor->bindParam("id_servico", $id_service);
        $checkCursor->execute();

        if ($checkCursor->fetchColumn() > 0) {
            return [false, "serviço já assinado"];
        }

        $command = "INSERT INTO servicos_funcionarios (id_funcionario, id_servico) VALUES (:id_funcionario, :id_servico);";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id_funcionario", $id_worker);
        $cursor->bindParam("id_servico", $id_service);
        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getRelateWorkersServicesReport(){
    if (!checkPermission("services")) return;

    try {
        global $pdo;

        $command = "SELECT f.id AS funcionario_id, f.nome AS funcionario_nome, GROUP_CONCAT(s.nome ORDER BY s.nome ASC SEPARATOR ', ') AS servicos, COUNT(s.id) AS quantidade_servicos, SUM(s.preco) AS total_gastos_servicos FROM funcionarios f JOIN servicos_funcionarios sf ON f.id = sf.id_funcionario JOIN servicos s ON sf.id_servico = s.id GROUP BY f.id, f.nome ORDER BY total_gastos_servicos DESC;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $services = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $services];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function deleteService($id) {
    if (!checkPermission("services")) return;

    try {
        global $pdo;
        $checkCommand = "SELECT COUNT(*) FROM servicos_funcionarios WHERE id_servico = :id";
        $checkCursor = $pdo->prepare($checkCommand);
        $checkCursor->bindParam(':id', $id);
        $checkCursor->execute();
        
        $result = $checkCursor->fetch(PDO::FETCH_ASSOC);
        
        if ($result['COUNT(*)'] > 0) {
            return [false, "erro: o item possui relações com outros setores"];
        }

        $command = "DELETE FROM servicos WHERE id = :id";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam(':id', $id);
        $cursor->execute();
        
        return [true, "Serviço excluído com sucesso"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}