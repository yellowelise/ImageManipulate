<?php
ini_set('memory_limit', '32048M');
include("im.class.php");


/* default value */

$w = 800;
$h = 600;
$r = 0;
$c = 0;
/*end default*/

/*override if passed from $_REQUEST*/

foreach ($_REQUEST as $k=>$v)
 ${$k} = $v;


$im = new ImageManipulate();



$q=0;
foreach (glob("{media".DIRECTORY_SEPARATOR."*.jpg,media".DIRECTORY_SEPARATOR."*.jpeg,media".DIRECTORY_SEPARATOR."*.gif,media".DIRECTORY_SEPARATOR."*.png}",GLOB_BRACE) as $filename) 
 {
	 ${"filenamejpg".$q} = $filename;
	 $q++;
 }


for ($i=0;$i<rand(4,55);$i++)
 {
  $ra = rand(0,$q-1);
  $fna[] = array("filename"=>${"filenamejpg".$ra});
 } 

header('Content-Type: ' . $im->extension_to_image_type("jpg"));

$im->CachePath = (__DIR__).DIRECTORY_SEPARATOR . "im_cache".DIRECTORY_SEPARATOR;
 
$im->append_array($fna,$w,$h,$c,$r);

?>

