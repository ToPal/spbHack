<?php
	header("Content-type: image/png");
	$im = imagecreate(200,100) or 
		die("Ошибка генерации изображения");
	$coleur_fond = imagecolorallocate($im,255,0,0);
	ImagePng($im);
	

?>