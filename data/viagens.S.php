<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
require_once("../assets/php/class/class.seg.php");
session_start();
proteger();

$host="10.0.0.2";
$service="//10.0.0.2:1521/orcl";
$conn= new \PDO("oci:host=$host;dbname=$service","INTRANET","ifnefy6b9");

$destino=$_POST['destino'];
$solicitante=$_SESSION['usuarioId'];
$cont=$_POST;
$conteudo=serialize($cont);


$query3="INSERT INTO IN_AUTORIZACOES (TIPO, DESCRICAO, SOLICITANTE, CONTEUDO) VALUES ('6', 'Solicitação de viagem - '||:destino, :solicitante, :conteudo)";
$stmt3 = $conn->prepare($query3);
$stmt3->bindValue(':destino',$destino); 
$stmt3->bindValue(':solicitante',$solicitante);
$stmt3->bindValue(':conteudo',$conteudo);
$stmt3->execute();
header("Location: viagens.php");


?>
