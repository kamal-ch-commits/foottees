<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';        
$DB_PASS = '';             
$DB_NAME = 'foottees';

$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($db->connect_errno) {
  die('MySQL connection failed: ' . $db->connect_error);
}
$db->set_charset('utf8mb4');

if (!function_exists('h')) {
  function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('require_admin')) {
  function require_admin(){
    if (empty($_SESSION['user'])) { header('Location: /foottees/login.php'); exit; }
  }
}

if (!function_exists('db_all')) {
  function db_all($sql){
    global $db; $rows=[];
    if ($res = $db->query($sql)) { while($r = $res->fetch_assoc()) $rows[] = $r; }
    return $rows;
  }
}
if (!function_exists('db_one')) {
  function db_one($sql){
    global $db;
    if ($res = $db->query($sql)) return $res->fetch_assoc() ?: null;
    return null;
  }
}
if (!function_exists('db_value')) {
  function db_value($sql){
    $r = db_one($sql); return $r ? array_values($r)[0] : null;
  }
}
