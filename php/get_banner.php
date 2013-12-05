<?php
	header("Content-type: image/png");


  //дефолтные параментры
	$source = $_SERVER["DOCUMENT_ROOT"]."/d/img/prettycity_green.png";
	$font = "./ARIAL.TTF";

  //параметры из запроса


  //загружаем болванку c лого.	
	if (!file_exists($source) )
		echo "source file not found: ".$source;
	$image = imagecreatefrompng( $source );
	imageantialias($image, true);
	imagealphablending($image, true);
	imagesavealpha($image, true);
	list($imwidth,$imheight,$type,$attr) = getimagesize($source);

  //создаем болванку текста
	$textim  = imagecreatetruecolor($imwidth, $imheight);
	imageantialias($textim, false);
	imagealphablending($textim, true);
	imagesavealpha($textim, false);
	$alpha   = imagecolorallocatealpha($textim, 0, 0, 0, 127);
	$textcol = imagecolorallocatealpha($textim, 255, 255, 255, 0);
	imagefill($textim, 0, 0, $alpha);
	if (! file_exists( $font ) ) 
		echo "font file not found";
	$text_props = imagettftext($textim, 60, 0, 0, 100, $textcol, $font, "100%");

  //сливаем текст и лого
	$dst_im = $image;
	$src_im = $textim;
	$src_x  = $text_props[6];
	$src_y  = $text_props[7];
	$src_w  = $text_props[2] - $text_props[0] + 10;
	$src_h  = $text_props[3] - $text_props[5];
	$dst_w  = $imwidth;
	$dst_h  = $imheight;
	$dst_x  = ($dst_w - $src_w) / 2;
	$dst_y  = ($dst_h - $src_h) / 2 + 15;
	imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);




  //отдаем картинку
	/*
	echo "src_x = $src_x <br>";
	echo "src_y = $src_y <br>";
	echo "src_w = $src_w <br>";
	echo "src_h = $src_h <br>";

	echo "text_props 0  = ".$text_props[0]."<br>";
	echo "text_props 1  = ".$text_props[1]."<br><br>";
	echo "text_props 2  = ".$text_props[2]."<br>";
	echo "text_props 3  = ".$text_props[3]."<br><br>";
	echo "text_props 4  = ".$text_props[4]."<br>";
	echo "text_props 5  = ".$text_props[5]."<br><br>";
	echo "text_props 6  = ".$text_props[6]."<br>";
	echo "text_props 7  = ".$text_props[7]."<br>";*/

	//var_dump($text_props);
	//ImagePng($textim);
	ImagePng($dst_im);
?>