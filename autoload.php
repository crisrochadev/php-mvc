<?php

/**
 * AUTOLOAD DE CLASSES PARA O PACOTE 'Classes'
 * @param $classe
 */
function autoload($classe)
{
    $diretorioBase = __DIR__ . DIRECTORY_SEPARATOR;
    $classe = str_replace('App', 'app', $classe);
    $classe = $diretorioBase . str_replace('\\', DIRECTORY_SEPARATOR, $classe) . '.php';

    if (file_exists($classe) && !is_dir($classe)) {
        include $classe;
    }
}

spl_autoload_register('autoload');
