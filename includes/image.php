<?php
include('../classes/class.resize.php');
$op 		= isset($_GET['op']) ? $_GET['op']:'resize';
$width 		= isset($_GET['w']) ? $_GET['w']:'';
$height 	= isset($_GET['h']) ? $_GET['h']:'';
$img 		= isset($_GET['img'])?$_GET['img']:'';
$dir 		= isset($_GET['dir'])?$_GET['dir']:'';
$dim 		= isset($_GET['dim'])?$_GET['dim']:'';
$path_img 	= realpath("../$dir/");

//echo "<pre>$op<br>$dim<br>$width<br>$height<br>$img<br>$dir<br>$path_img</pre>";

$image = new SimpleImage();
$image->load($path_img.'/'.$img);
switch($op){
	case 'r2w': //resize2width
		$image->resizeToWidth($dim);
	break;	
	case 'r2h': //resize2height
		$image->resizeToHeight($dim);
	break;	
	case 's': //scale
		$image->scale($dim);
	break;	
	case 'r': //resize
		$image->resize($width,$height);
	break;	
}

$image->output();
?>