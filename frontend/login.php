<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }

    require_once dirname(__DIR__) . "/backend/global.php";
    require_once dirname(__DIR__) . "/backend/sectors/rh.php";
    require_once dirname(__DIR__) . "/global/utils.php";
        
    if (!empty($_POST)){

        $email = $_POST["email"];
        $password = $_POST["password"];

        if (empty($email) || empty($password)) {
            echo getErrorMessage("Todos os campos devem ser preenchidos");
        } else {
            
            $result = login($email, $password);
            
            if ($result[0]){
                if ($result[1] == "Success") {
                    $id = $result[2];
       
                    $_SESSION["permission"] = null;
                    $_SESSION["permission"] = "rh";
                    $userData = getWorker($id);
                    $userData = $userData[1];
                    $userPosition = getPostion($userData["id_cargo"]);
                    $userPosition = $userPosition[1];
                    $_SESSION["permission"] = null;
                    
                    $_SESSION["isLogged"] = "isLogged";
                    $_SESSION["permission"] = $userPosition["nome"];
                    $_SESSION["userName"] = $userData["nome"];
                    $_SESSION["userId"] = $userData["id"];
                    $_SESSION["userPositionId"] = $userData["id_cargo"];

                    header("Location: ./index.php");
                } else {
                    $_SESSION["userTempId"] = $result[2];
                    header("Location: ./setNewPassword.php");
                    exit();
                }

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
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .left-panel {
            flex: 1;
            background-color: #172642;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .eclipse-background {
            position: absolute;
            width: 350px;
            height: 350px;
            background-image: url('Ellipse 1.png');
            background-size: cover;
            background-position: center;
            z-index: 1;
        }

        .logo {
            position: relative;
            max-width: 400px;
            z-index: 2;
        }

        .right-panel {
            flex: 1;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-title {
            font-size: 4rem;
            color: #333;
            margin-bottom: 20px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        .form-group {
            width: 100%;
            max-width: 300px;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 1.5rem;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #eaeaea;
        }

        .login-button {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #15243e;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #415276;
        }

        @media (max-width: 480px) {
            .container {
                flex-direction: column; 
            }

            .left-panel {
                flex: none; 
                width: 100%; 
                height: 25%; 
            }

            .right-panel {
                flex: none; 
                width: 100%;
                padding: 20px;
            }

            .eclipse-background {
                width: 150px; 
                height: 150px;
            }

            .logo {
                max-width: 120px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="eclipse-background"></div>
            <img src="./images/logo.png" alt="Logo WorkVision" class="logo">
        </div>

        <div class="right-panel">
            <h1 class="login-title">login</h1>
            <form method="post">
                <div class="form-group">
                    <label for="email">e-mail</label>
                    <input type="email" id="email" name="email" placeholder="digite seu e-mail">
                </div>
                <div class="form-group">
                    <label for="senha">senha</label>
                    <input type="password" id="senha" name="password" placeholder="digite sua senha">
                </div>
                <button type="submit" class="login-button">entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
