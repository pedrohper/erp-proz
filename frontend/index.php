<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if (empty($_SESSION["isLogged"]) || $_SESSION["isLogged"] != "isLogged"){   
        header("Location: ./login.php");
        exit();
    }

    require_once dirname(__DIR__) . "/backend/global.php";

    $allowedPages = getAllowedPages($_SESSION["userPositionId"]);
    $allowedPages = $allowedPages[1];
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./sectors/style.css">
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
                    <?php 
                        foreach ($allowedPages as $page){
                            echo "<li><a style='color:".$page['cor'].";' class='pageLink' href='" . $page["url"] . "'>" . $page["nome"] . "</a></li>";
                        }
                    ?>

                    <li>         
                        <form action="./logoff.php" method="post">
                            <input id="logout" type="submit" value="Sair da conta">
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <div id="content">
            <h1>bem vindo, <?php echo $_SESSION["userName"]; ?>.</h1>

            <p style="text-align: center;margin-top: 20px;">seu cargo é <strong><?php echo $_SESSION["permission"]; ?></strong>.</p>
            <p style="text-align: center;margin-top: 5px;">você pode navegar pelo menu lateral.</p>
        </div>
    </main>

</body>
</html>