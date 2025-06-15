<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
date_default_timezone_set("Asia/Kathmandu");
$host="localhost";
$db="portfolio";
$u = "root";
$p="";
$conn = mysqli_connect($host,$u,$p,$db) or die("Error while connecting database");
?>