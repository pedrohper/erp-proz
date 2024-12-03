<?php

function validateCPF($cpf) {
    $cpf = preg_replace('/[^\d]/', '', $cpf);

    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $sum = 0;
        for ($i = 0; $i < $t; $i++) {
            $sum += $cpf[$i] * (($t + 1) - $i);
        }
        $digit = (($sum * 10) % 11) % 10;
        if ($cpf[$t] != $digit) {
            return false;
        }
    }

    return $cpf;
}

function getErrorMessage($error) {
    return "<div style='z-index:20;position:absolute;left:50%;top:15px;transform:translateX(-50%);background-color:red;padding:10px;border-radius:5px;'><p style='color:white;text-align:center;'>$error</p></div>";
}

function getSuccessMessage($msg) {
    return "<div style='z-index:20;position:absolute;left:50%;top:15px;transform:translateX(-50%);background-color:green;padding:10px;border-radius:5px;'><p style='color:white;text-align:center;'>$msg</p><br><p style='font-size:10px;color:white;text-align:center;;'>alterações só serão refletidas nesta página após atualizar</p></div>";
}