<?php

require_once dirname(__DIR__ ) ."/global.php";
require_once dirname(__DIR__ ) ."/connection.php";

function registerProduct($name, $description, $price, $quantity, $id_supplier){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "INSERT INTO produtos (nome, descricao, preco, quantidade, id_fornecedor) VALUES (:nome, :descricao, :preco, :quantidade, :id_fornecedor);";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome", $name);
        $cursor->bindParam("descricao", $description);  
        $cursor->bindParam("preco", $price);
        $cursor->bindParam("quantidade", $quantity);
        $cursor->bindParam("id_fornecedor", $id_supplier);

        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function updateProduct($name, $description, $price, $quantity, $id_supplier, $id){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;
        
        $command = "UPDATE produtos SET nome=:nome, descricao=:descricao, preco=:preco, quantidade=:quantidade, id_fornecedor=:id_fornecedor WHERE id=:id;";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome", $name);
        $cursor->bindParam("descricao", $description);  
        $cursor->bindParam("preco", $price);
        $cursor->bindParam("quantidade", $quantity);
        $cursor->bindParam("id_fornecedor", $id_supplier);
        $cursor->bindParam("id", $id);

        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getAllProducts(){
    if ((!checkPermission("stock"))  && (!checkPermission("sales"))) return;

    try {
        global $pdo;

        $command = "SELECT * FROM produtos";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $products = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $products];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getProduct($id){
    if ((!checkPermission("stock"))  && (!checkPermission("sales"))) return;

    try {
        global $pdo;

        $command = "SELECT * FROM produtos WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id", $id);
        $cursor->execute();
        $product = $cursor->fetch(PDO::FETCH_ASSOC);

        return [true, $product];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getProductsReport(){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "SELECT p.nome AS produto_nome, p.quantidade AS estoque_atual, p.preco AS preco_atual, IFNULL(SUM(c.quantidade), 0) AS vezes_vendido, IFNULL(SUM(c.preco), 0) AS total_vendido FROM produtos p LEFT JOIN compras c ON p.id = c.id_produto GROUP BY p.id, p.nome, p.quantidade, p.preco ORDER BY total_vendido DESC;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $products = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $products];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function deleteProduto($id) {
    if (!checkPermission("stock")) return;

    try {
        global $pdo;
        
        $checkCommand = "SELECT COUNT(*) FROM compras WHERE id_produto = :id";
        $checkCursor = $pdo->prepare($checkCommand);
        $checkCursor->bindParam(':id', $id);
        $checkCursor->execute();
        
        $result = $checkCursor->fetch(PDO::FETCH_ASSOC);
        
        if ($result['COUNT(*)'] > 0) {
            return [false, "erro: o item possui relações com outros setores"];
        }

        $command = "DELETE FROM produtos WHERE id = :id";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam(':id', $id);
        $cursor->execute();
        
        return [true, "Produto excluído com sucesso"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function registerSupplier($nome, $cnpj, $telefone, $endereco, $email){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "INSERT INTO fornecedores (nome, cnpj, telefone, endereco, email) VALUES (:nome, :cnpj, :telefone, :endereco, :email);";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome", $nome);
        $cursor->bindParam("cnpj", $cnpj);
        $cursor->bindParam("telefone", $telefone);
        $cursor->bindParam("endereco", $endereco);
        $cursor->bindParam("email", $email);

        $cursor->execute();

        return [true, "Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function updateSupplier($nome, $cnpj, $telefone, $endereco, $email, $id){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "UPDATE fornecedores SET nome=:nome, cnpj=:cnpj, telefone=:telefone, endereco=:endereco, email=:email WHERE id=:id;";
        $cursor = $pdo->prepare($command);

        $cursor->bindParam("nome", $nome);
        $cursor->bindParam("cnpj", $cnpj);
        $cursor->bindParam("telefone", $telefone);
        $cursor->bindParam("endereco", $endereco);
        $cursor->bindParam("email", $email);
        $cursor->bindParam("id", $id);

        $cursor->execute();

        return [true,"Success"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getAllSuppliers(){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM fornecedores;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $suppliers = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $suppliers];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getSupplier($id){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "SELECT * FROM fornecedores WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id", $id);
        $cursor->execute();
        $supplier = $cursor->fetch(PDO::FETCH_ASSOC);

        return [true, $supplier];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function getSuppliersReport(){
    if (!checkPermission("stock")) return;

    try {
        global $pdo;

        $command = "SELECT f.id AS id_fornecedor, f.nome AS fornecedor_nome, GROUP_CONCAT(p.nome ORDER BY p.nome ASC SEPARATOR ', ') AS produtos, COUNT(c.id) AS total_compras FROM fornecedores f LEFT JOIN produtos p ON f.id = p.id_fornecedor LEFT JOIN compras c ON p.id = c.id_produto GROUP BY f.id, f.nome ORDER BY total_compras DESC;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $suppliers = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $suppliers];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}

function deleteSupplier($id) {
    if (!checkPermission("stock")) return;
    
    try {
        global $pdo;
    
        $checkCommand = "SELECT COUNT(*) FROM produtos WHERE id_fornecedor = :id";
        $checkCursor = $pdo->prepare($checkCommand);
        $checkCursor->bindParam(':id', $id);
        $checkCursor->execute();
        
        $result = $checkCursor->fetch(PDO::FETCH_ASSOC);
        
        if ($result['COUNT(*)'] > 0) {
            return [false, "erro: o item possui relações com outros setores"];
        }

        $command = "DELETE FROM fornecedores WHERE id = :id";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam(':id', $id);
        $cursor->execute();
        
        return [true, "Fornecedor excluído com sucesso"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}