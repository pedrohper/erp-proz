<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if ($_SESSION["permission"] != "admin" && $_SESSION["permission"] != "rh"){
        header("Location: ../../index.php");
        exit();
    }

    require_once dirname(dirname(path: dirname(path: __DIR__))) . "/global/utils.php";
    require_once dirname(dirname(dirname(__DIR__))) . "/backend/sectors/rh.php";
    require_once dirname(dirname(dirname(__DIR__))) . "/backend/global.php";
    
    $allowedPages = getAllowedPages($_SESSION["userPositionId"]);
    $allowedPages = $allowedPages[1];
    $allPositions = getAllPositions();
    $allPositions = $allPositions[1];

    if (!empty($_GET)){
        $id = $_GET["selectedWorker"];
        $worker = getWorker($id);
        $worker = $worker[1];
        
        $name = $worker["nome"];
        $cpf = $worker["cpf"];
        $phone = $worker["telefone"];
        $birthday = $worker["nascimento"];
        $email = $worker["email"];
        $address = $worker["endereco"];
        $salary = $worker["salario"];
        $admission = $worker["admissao"];
        $position_id = $worker["id_cargo"];

        $currentPosition = getPostion($position_id);
        $currentPosition = $currentPosition[1];

    } else {
        header("Location: ./selectWorkerToUpdate.php");   
    }

    if (!empty($_POST)){
        $name = $_POST["name"];
        $cpf = $_POST["cpf"];
        $birthday = $_POST['birthday'];
        $address = $_POST["address"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $salary = $_POST["salary"]; 
        $admission = $_POST["admission-date"];
        $selectedPosition = $_POST["selectedPosition"];

        if (empty($name) || empty($cpf) || empty($birthday) || empty($address) || empty($phone) || empty($email) || empty($salary) || empty($admission) || empty($position_id) || empty($selectedPosition)){
            echo getErrorMessage("Todos os campos devem ser preenchidos");
        } else {
            $cpf = validateCPF($cpf);

            if ($cpf === false){
                echo getErrorMessage("CPF inválido");
            
            } else {
            
                $result = updateWorker($name, $cpf, $address, $phone, $birthday, $admission, $salary, $email, $selectedPosition, $id);
            
                if ($result[0]){
                    echo getSuccessMessage("Funcionario atualiazdo com sucesso");

                } else {
                    echo getErrorMessage($result[1]);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar</title>

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
            <h1>atualizar funcionário</h1>

            <form method="post">
                <label for="name">nome:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>">

                <label for="cpf">cpf:</label>
                <input type="text" id="cpf" name="cpf" value="<?php echo $cpf; ?>">

                <label for="position">cargo: (atual) = <?php echo $currentPosition["nome"]; ?></label>
                <select name="selectedPosition" id="position">
                    <?php
                        foreach ($allPositions as $position){
                            echo "<option value='" . $position["id"] . "'>" . $position["nome"] . "</option>";
                        }
                    ?>
                </select>
                
                <label for="birthday">data de nascimento:</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo $birthday; ?>">

                <label for="address">endereço:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>">

                <label for="phone">telefone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">

                <label for="admission-date">data de admissão:</label>
                <input type="date" id="admission-date" name="admission-date" value="<?php echo $admission; ?>">

                <label for="email">email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">

                <label for="salary">salário:</label>
                <input type="number" step="0.01" min="0" id="salary" name="salary" value="<?php echo $salary; ?>">

                <input type="submit" id="submit" value="atualizar">
            </form>
        </div>
    </main>

</body>
</html>