<?php

try {

$mysqlClient =  new PDO(

    'mysql:host=localhost;dbname=jo;charset=utf8',

    'root',

    ''

);

} catch(PDOexception $e){

    die($e->getMessage());

}

$query = $mysqlClient->prepare("select * from jo.`100`;");

$query->execute();

$data = $query->fetchAll();

$mysqlClient = null;

$dbh = null;

?>
<table>
<thead>
<tr>
<th>Nom</th>
<th>Pays</th>
<th>Course</th>
<th>Temps</th>
</tr>
</thead>
<?php foreach($data as $value) { ?>
<tr>
<td><?php echo $value["nom"]; ?></td>
<td><?php echo $value["pays"]; ?></td>
<td><?php echo $value["course"]; ?></td>
<td><?php echo $value["temps"]; ?></td>
</tr>
<?php }  ?>
</table>
<?php

 