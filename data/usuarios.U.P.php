<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
require_once("../assets/php/class/class.seg.php");
session_start();
proteger();

$host="10.0.0.2";
$service="//10.0.0.2:1521/orcl";
$email=$_SESSION['usuarioEmail'];
$conn= new \PDO("oci:host=$host;dbname=$service","INTRANET","ifnefy6b9");

$idu=$_POST['id'];
$email=$_POST['email'];
$senha=$_POST['senha'];
$nome=$_POST['nome'];
$cargo=$_POST['cargo'];
$ramal=$_POST['ramal'];
$im=$_POST['im'];
$local=$_POST['local'];
$admissao=$_POST['admissao'];
$sobrenome=$_POST['sobrenome'];

$query3="UPDATE 
            IN_USUARIOS 
        SET 
            EMAIL = :email, 
            SENHA = :senha, 
            NOME = :nome,             
            CARGO = :cargo, 
            RAMAL = :ramal, 
            IM = :im, 
            LOCAL = :local, 
            ADMISSAO = :admissao, 
            SOBRENOME = :sobrenome
        WHERE ID = :idu";

$stmt3 = $conn->prepare($query3);
$stmt3->bindValue(':idu',$idu);
$stmt3->bindValue(':email',$email);
$stmt3->bindValue(':senha',$senha);
$stmt3->bindValue(':nome',$nome);
$stmt3->bindValue(':setor',$setor);
$stmt3->bindValue(':cargo',$cargo);
$stmt3->bindValue(':ramal',$ramal);
$stmt3->bindValue(':im',$im);
$stmt3->bindValue(':local',$local);
$stmt3->bindValue(':admissao',$admissao);
$stmt3->bindValue(':sobrenome',$sobrenome);

$stmt3->execute();
header('Location: ' . $_SERVER['HTTP_REFERER']);


?>
