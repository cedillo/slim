<?php  
require 'Slim/Slim.php';
require 'conexion.php';

$db=new conexion();
$db->conecta('localhost','MV-SERVER2012','ascm_sia','sa','.sqlCota13.');

/* Register autoloader and instantiate Slim */
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array(
    'templates.path' => './templates'
));

$app->get('/fire', function() use ($app){
	$app->render('saludo.php');

});

/* Run the application */
$app->run();
?>

