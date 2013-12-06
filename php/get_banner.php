<?php
	include 'php/main_funcs.php';

	header("Content-type: image/png");



  //дефолтные параментры
	$source = $_SERVER["DOCUMENT_ROOT"]."/d/img/prettycity_green.png";
	$font = "php/ARIAL.TTF";
	$fontSize = 70;

  //параметры из запроса

  //получаем рейтинг


  //загружаем болванку c лого.	
	if (!file_exists($source) )
		echo "source file not found: ".$source;
	$image = imagecreatefrompng( $source );
	imageantialias($image, true);
	imagealphablending($image, true);
	imagesavealpha($image, true);
	list($imwidth,$imheight,$type,$attr) = getimagesize($source);

  //получаем рейтинг
	if ( isset($_REQUEST['address']) )
		$address = $_REQUEST['address'];
	else
		$address = "Фрунзенская, 28";
	$raiting = func_getRaitingByAddress( $address );
	$raiting = round( $raiting['raiting'] );
	$raiting = 100;

  //создаем болванку текста
	$textim  = imagecreatetruecolor($imwidth, $imheight);
	imageantialias($textim, true);
	imagealphablending($textim, true);
	imagesavealpha($textim, false);
	$alpha   = imagecolorallocatealpha($textim, 0, 0, 0, 127);
	$textcol = imagecolorallocatealpha($textim, 255, 255, 255, 0);
	imagefill($textim, 0, 0, $alpha);
	if (! file_exists( $font ) ) 
		echo "font file not found";
	$text_props = imagettftext($textim, $fontSize, 0, 0, 100, $textcol, $font, $raiting."%");

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

  //ресайзим ресайзу чгоблин картинку
	if ( isset( $_REQUEST['size'] ) ){
		$rsize = $_REQUEST['size'];
		$res_im = imagecreatetruecolor($rsize, $rsize);
		imageantialias($res_im, true);
		imagealphablending($res_im, false);
		imagesavealpha($res_im, true);
		imagecopyresampled(
			$dst_image = $res_im, 
			$src_image = $dst_im, 
			$dst_x = 0, 
			$dst_y = 0, 
			$src_x = 0, 
			$src_y = 0, 
			$dst_wi = $rsize, 
			$dst_hi = $rsize, 
			$src_wi = $imwidth , 
			$src_hi = $imheight);
		$dst_im = $res_im;

		//echo " rsize = $rsize <br>";
	}else{
		//echo "size not found";
	}



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