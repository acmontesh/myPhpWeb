<?php

define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');

function IncluirPlantilla( string $nombrePlantilla, bool $inicio=false) {
    include TEMPLATES_URL . "/${nombrePlantilla}.php";
}




?>