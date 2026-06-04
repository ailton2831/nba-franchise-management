<?php
// Substitui 'nba123' pela password que tu realmente queres para o teu Admin
$password_limpa = 'admin'; 

$password_com_hash = password_hash($password_limpa, PASSWORD_DEFAULT);

echo "<h3>Copia o código abaixo para o phpMyAdmin:</h3>";
echo "<code style='font-size:18px; background:#f4f4f4; padding:5px 10px; border:1px solid #ccc;'>" . $password_com_hash . "</code>";
?>