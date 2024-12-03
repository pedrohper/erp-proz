<?php

$_SESSION["permission"] = "admin";
require_once "sectors/rh.php";

$res = createWorker("admin", "123.456.789-09", "Rua 1", "34984125717", "2006/01/27", "2024/11/25", 5000, "proz@gmail.com", "proz", 1, 1);

?> 