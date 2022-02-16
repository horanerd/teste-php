<?php

/*****
 * 
 * Funções
 * 
 */

// Função para calcular a idade
function calcularIdade($data)
{
    $idade = 0;
    $data_nascimento = date('Y-m-d', strtotime($data));
    list($anoNasc, $mesNasc, $diaNasc) = explode('-', $data_nascimento);

    $idade      = date("Y") - $anoNasc;
    if (date("m") < $mesNasc) {
        $idade -= 1;
    } elseif ((date("m") == $mesNasc) && (date("d") <= $diaNasc)) {
        $idade -= 1;
    }

    return $idade;
}

// Função de retorno de datas
function date_fmt(?string $date, string $format = "d/m/Y"): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format($format);
}
