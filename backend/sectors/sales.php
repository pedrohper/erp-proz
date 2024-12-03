<?php

require_once dirname(__DIR__ ) ."/global.php";

function registerSale($worker_id, $product_id, $price, $quantity, $date){
    if (!checkPermission("sales")) return;
    global $pdo;

    try {
        $command = "SELECT quantidade FROM produtos WHERE id=:id;";
        $cursor = $pdo->prepare($command);
        $cursor->bindParam("id", $product_id);
        $cursor->execute();
        $product = $cursor->fetch(PDO::FETCH_ASSOC);
        $currentQuantity = $product["quantidade"];

        $futureQuantity = $currentQuantity - $quantity;

        if ($futureQuantity < 0) return [false, "Estoque Insuficiente, quantidade Atual: $currentQuantity"];
        
        try {

            $pdo->beginTransaction();

            $command = "INSERT INTO compras (id_funcionario, id_produto, preco, quantidade, data) VALUES (:id_funcionario, :id_produto, :preco, :quantidade, :data);";
            $cursor = $pdo->prepare($command);
            
            $cursor->bindParam("id_funcionario", $worker_id);
            $cursor->bindParam("id_produto", $product_id);
            $cursor->bindParam("preco", $price);
            $cursor->bindParam("quantidade", $quantity);
            $cursor->bindParam("data", $date);
    
            $cursor->execute();

            $command = "UPDATE produtos SET quantidade=:quantidade WHERE id=:id;";
            $cursor = $pdo->prepare($command);

            $cursor->bindParam("quantidade", $futureQuantity);
            $cursor->bindParam("id", $product_id);

            $cursor->execute();

            $pdo->commit();
            return [true, "Sucess"];
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return [false, $e->getMessage()];
        }

    } catch (Exception $e) {
        return [false, $e->getMessage()];
    }
}

function getSalesReport(){
    if (!checkPermission("sales")) return;

    try {
        global $pdo;

        $command = "SELECT c.preco AS preco_compra, c.quantidade AS quantidade_comprada, c.data AS data_compra, f.nome AS nome_comprador, p.nome AS nome_produto FROM compras c JOIN funcionarios f ON c.id_funcionario = f.id JOIN produtos p ON c.id_produto = p.id ORDER BY c.data DESC;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $sales = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $sales];
    } catch (Exception $e) {
        return [false, $e->getMessage()];
    }
}