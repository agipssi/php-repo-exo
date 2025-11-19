<?php

 try {

     $mysqlClient = new PDO (

         'mysql:host=localhost;dbname=jo;charset=utf8',

         'root',

         ''

     );

 } catch(PDOException $e){

     die($e->getMessage());

 }
 
$sort = "nom";  

 if (isset($_GET['sort'])) {

     $sort = $_GET['sort'];
 }
 
$order = "asc";  

 if (isset($_GET['order'])) {

     $order = $_GET['order'];

 }
 
 
$allowedSort = ["nom", "pays", "course", "temps"];

 $allowedOrder = ["asc", "desc"];
 
if (!in_array($sort, $allowedSort)) {

     $sort = "nom";

 }
 
if (!in_array($order, $allowedOrder)) {

     $order = "asc";

 }
 

$sql = "SELECT * FROM jo.`100` ORDER BY $sort $order";
 
$query = $mysqlClient->prepare($sql);

 $query->execute();

 $data = $query->fetchAll();
 
$mysqlClient = null;

 ?>
<h2>Trier par :</h2>
 
<p>

     Nom :
<a href="?sort=nom&order=asc">↑</a>
<a href="?sort=nom&order=desc">↓</a>
</p>
 
<p>

     Pays :
<a href="?sort=pays&order=asc">↑</a>
<a href="?sort=pays&order=desc">↓</a>
</p>
 
<p>

     Course :
<a href="?sort=course&order=asc">↑</a>
<a href="?sort=course&order=desc">↓</a>
</p>
 
<p>

     Temps :
<a href="?sort=temps&order=asc">↑</a>
<a href="?sort=temps&order=desc">↓</a>
</p>
 
<hr>
 
<h2>Résultats :</h2>
 
<?php foreach ($data as $value): ?>
 
<p>
<strong>Nom :</strong> <?= $value["nom"] ?><br>
<strong>Pays :</strong> <?= $value["pays"] ?><br>
<strong>Course :</strong> <?= $value["course"] ?><br>
<strong>Temps :</strong> <?= $value["temps"] ?><br>
</p>
 
<hr>
 
<?php endforeach; ?>
 