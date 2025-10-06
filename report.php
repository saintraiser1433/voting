<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("libraries/assets/phpjasperxml/PHPJasperXML.inc.php");
$server = "localhost";
$db = "myvote";
$user = "root";
$pass = "";


$PHPJasperXML = new PHPJasperXML();
// $PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter = array("grade" => 12);
$PHPJasperXML->load_xml_file("libraries/assets/report/test2.jrxml");

$PHPJasperXML->transferDBtoArray($server, $user, $pass, $db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file