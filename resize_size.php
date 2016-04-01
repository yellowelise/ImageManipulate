<?php
ini_set('memory_limit', '32048M');
include("class/im.class.php");

$w = 800;
$h = 600;
$c = 0;
/*end default*/

/*override if passed from $_REQUEST*/

foreach ($_REQUEST as $k=>$v)
 ${$k} = $v;

$im = new ImageManipulate();
$q=0;
foreach (glob("{media".DIRECTORY_SEPARATOR."*.jpg,media".DIRECTORY_SEPARATOR."*.jpeg,media".DIRECTORY_SEPARATOR."*.gif,media".DIRECTORY_SEPARATOR."*.png}",GLOB_BRACE) as $filename) 
 {
	 ${"filename".$q} = $filename;
	 $q++;
 }


  $ra = rand(0,$q-1);
  $filename = ${"filename".$ra};
  $ext = strtolower(substr(strrchr($filename, '.'), 1));  
  header('Content-Type: ' . $im->extension_to_image_type($ext));

  $im->CachePath = (__DIR__).DIRECTORY_SEPARATOR . "im_cache".DIRECTORY_SEPARATOR;

  $im->resize($filename,$w,$h,$c);

?>

