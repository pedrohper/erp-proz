<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    
    if ($_SESSION["permission"] != "admin" && $_SESSION["permission"] != "rh"){
        header("Location: ../../index.php");
        exit();
    }

    require_once dirname(dirname(dirname(__DIR__))) . "/backend/sectors/rh.php";
    require_once dirname(dirname(dirname(__DIR__))) . "/backend/global.php";
    require_once dirname(dirname(dirname(__DIR__))) . "/global/utils.php";
    
    $allowedPages = getAllowedPages($_SESSION["userPositionId"]);
    $allowedPages = $allowedPages[1];

    $reportWorkers = getWorkersReport();
    $reportWorkers = $reportWorkers[1];

    if (!empty($_POST)){
        $id = $_POST["id"];

        if (empty($id)){
            echo getErrorMessage("Todos os campos devem ser preenchidos");
        } else {
            $result = deleteWorker($id);
        
            if ($result[0]){
                echo getSuccessMessage("Funcionario deletado com sucesso"); 
                header("Location: ./reportWorkers.php");
            } else {
                echo getErrorMessage($result[1]);
            }
        }
    }
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
                            echo "<li><a  style='color:".$page['cor'].";' class='pageLink' href='" . $page["url"] . "'>" . $page["nome"] . "</a></li>";
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
            <h1>quadro de funcionários</h1>

            <table>
                <tr>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Salário</th>
                    <th>Data de admissão</th>
                    <th>Ação</th>
                </tr>

                <?php
                    foreach ($reportWorkers as $worker){
                        echo "<tr>";
                        echo "<td>" . $worker["funcionario_nome"] . "</td>";
                        echo "<td>" . $worker["cargo_nome"] . "</td>";
                        echo "<td>R$ " . $worker["funcionario_salario"] . "</td>";
                        echo "<td>" . $worker["funcionario_admissao"] . "</td>";
                        echo "<td>"."<form method='post'><input type='hidden' name='id' value='" . $worker["funcionario_id"] . "'><input class='deleteButton' type='submit' value='Deletar'>"."</form>"."</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
    </main>

</body>
</html>