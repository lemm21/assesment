<?php
// Itong page na to yung nag ddisplay ng image pag naka blob yung image sa database. 
include "db.php";

if (!isset($_GET["id"])) die("No ID Specified");
// ^^ meaning nung walang id sa url, hindi matuloy yung page.
$id = intval($_GET["id"]);
// ^^ since string yung id sa url, kailangan natin i convert sa integer para magamit sa query.

$sql = "SELECT picture FROM products WHERE product_id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) die("No image found.");

$row = $result->fetch_assoc();
$finfo = finfo_open();
$type = finfo_buffer($finfo, $row["picture"], FILEINFO_MIME_TYPE);
finfo_close($finfo);
// ^^ since blob yung image sa database, kailangan natin i detect yung type ng image para ma display ng browser.
header("Content-Type: $type");
echo $row["picture"];
// para ma display mo yung image type mo lang...
// <img src="showImage.php?id= <?php echo $id; ?.>">
// bakit id? naka echo na kasi yung table column ni image dito sa file, kaya yung id sa url is yung product_id para ma display yung image sa ibang page.



