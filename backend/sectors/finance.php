<?php

require_once dirname(__DIR__ ) ."/global.php";
require_once dirname(__DIR__ ) ."/connection.php";

function getFinanceReport(){
    if (!checkPermission("finance")) return;

    try {
        global $pdo;

        $command = "SELECT f.id AS funcionario_id, f.nome AS funcionario_nome, IFNULL(SUM(c.preco * c.quantidade), 0) AS total_gastos_compras, IFNULL(SUM(s.preco), 0) AS total_gastos_servicos, (IFNULL(SUM(c.preco * c.quantidade), 0) + IFNULL(SUM(s.preco), 0)) AS total_gastos FROM funcionarios f LEFT JOIN compras c ON f.id = c.id_funcionario LEFT JOIN servicos_funcionarios sf ON f.id = sf.id_funcionario LEFT JOIN servicos s ON sf.id_servico = s.id GROUP BY f.id, f.nome ORDER BY total_gastos DESC;";
        $cursor = $pdo->prepare($command);
        $cursor->execute();
        $finance = $cursor->fetchAll(PDO::FETCH_ASSOC);

        return [true, $finance];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}