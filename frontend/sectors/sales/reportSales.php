<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    
    if ($_SESSION["permission"] != "admin" && $_SESSION["permission"] != "sales"){
        header("Location: ../../index.php");
        exit();
    }

    require_once dirname(dirname(dirname(__DIR__))) . "/backend/sectors/sales.php";
    require_once dirname(dirname(dirname(__DIR__))) . "/backend/global.php";
    
    $allowedPages = getAllowedPages($_SESSION["userPositionId"]);
    $allowedPages = $allowedPages[1];

    $reportSales = getSalesReport();
    $reportSales = $reportSales[1];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    
    <header>
        <div id="logo">
            <div id="logo-image"></div>
            <p>WorkVision</p>
        </div>

        <div id="user-image"></div>
    </header>

    <main>
        <aside>
            <nav>
                <ul>
                    <li id="homeLinkLi">
                        <a id="homeLink" href="/erp-proz/frontend/index.php">Tela inicial</a>
                    </li>
                    <?php 
                        foreach ($allowedPages as $page){
                            echo "<li><a style='color:".$page['cor'].";'  class='pageLink' href='" . $page["url"] . "'>" . $page["nome"] . "</a></li>";
                        }
                    ?>

                    <li>         
                        <form action="../../logoff.php" method="post">
                            <input id="logout" type="submit" value="Sair da conta">
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <div id="content">
            <h1>relatório de vendas</h1>

            <table>
                <tr>
                    <th>Nome</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Data</th>
                </tr>

                <?php
                    foreach ($reportSales as $sales){
                        echo "<tr>";
                        echo "<td>" . $sales["nome_comprador"] . "</td>";
                        echo "<td>" . $sales["nome_produto"] . "</td>";
                        echo "<td>R$ " . $sales["preco_compra"] . "</td>";
                        echo "<td>" . $sales["quantidade_comprada"] . "</td>";
                        echo "<td>" . $sales["data_compra"] . "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
    </main>

</body>
</html>