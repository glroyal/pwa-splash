<?php

/***************************************************************************
 *
 * splash.php (c) 2022 Gary Royal
 * 
 * Generates complete set of PWA splash screens for Apple devices, 
 * with list of meta tags
 * 
 ***************************************************************************/
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

$bg_color ="212529"; // splash screen background color

$logo_file = "./pwalogo.png"; //512px or larger, solid, no alpha (transparency)

$logo_scale = 0.25; // 25% of device width 

// where to build output folder 
//
$document_root = "."; 

// 
// output folder name
// 
$location = "splash_screens";

$outpath = "{$document_root}/$location";

$enable_landscape = false; 

$meta = [];

$splash_size = [
	[1024, 1366, 2, 2048, 2732, 'iPad Pro 12.9'],
	[ 834, 1194, 2, 1668, 2388, 'iPad Pro 11, iPad Pro 10.5'],
	[ 820, 1180, 2, 1640, 2360, 'iPad Air 10.9'],
	[ 834, 1112, 2, 1668, 2224, 'iPad Air 10.5'],
	[ 810, 1080, 2, 1620, 2160, 'iPad 10.2'],
	[ 768, 1024, 2, 1536, 2048, 'iPad Pro 7.9, iPad Mini 7.9, iPad Air 9.7, iPad'],
	[ 430,  932, 3, 1290, 2796, 'iPhone 14 Pro Max'],
	[ 393,  852, 3, 1179, 2556, 'iPhone 14 Pro'],
	[ 428,  926, 3, 1284, 2778, 'iPhone 14 Plus, iPhone 13 Pro Max, iPhone 12 Pro Max'],
	[ 390,  844, 3, 1170, 2532, 'iPhone 14, iPhone 13 Pro, iPhone 13, iPhone 12 Pro, iPhone 12'],
	[ 375,  812, 3, 1125, 2436, 'iPhone 13 Mini, iPhone 12 Mini, iPhone 11 Pro, iPhone XS, iPhone X'],
	[ 414,  896, 3, 1242, 2688, 'iPhone 11 Pro Max, iPhone XS Max'],
	[ 414,  736, 3, 1242, 2208, 'iPhone 8 Plus, iPhone 7 Plus, iPhone 6s Plus, iPhone 6 Plus'],	
	[ 414,  896, 2,  828, 1792, 'iPhone 11, iPhone XR'],
	[ 375,  667, 2,  750, 1334, 'iPhone 8, iPhone 7, iPhone 6s, iPhone 6, iPhone SE'],
	[ 320,  568, 2,  640, 1136, 'iPhone SE, iPod Touch 5'],
];


if(!is_dir($outpath)) {
	mkdir($outpath, 0775, true);
}


function even($n) {
	return ($n % 2 == 0) ? $n : $n+1;
}


function emit_splash() {

	global  $cw,	// css width 
			$ch,	// css height 
			$dpr, 	// device pixel ratio
			$rw,	// render_width 
			$rh, 	// render_height
			$red, $grn, $blu,	// RGB background color
			$src_image,	// logo image
			$src_width,	// logo width
			$src_height,	// logo height	
			$aspect,	// logo aspect ratio
			$logo_scale, // size of logo (0.25 = 25%) 
			$location,	// relative path for html
			$outpath;	// output directory 

	$dst_size = even(min($rw,$rh) * $logo_scale);

	if($aspect>1) {

		// landscape 

		$dst_width = $dst_size;					
		$dst_height = (int)($dst_size/$aspect);	

	} else {

		// portrait

		$dst_width = (int)($dst_size*$aspect);	
		$dst_height = $dst_size;
	}		

	$dst_image = @ImageCreateTrueColor($rw, $rh);
	
	$dst_color = imagecolorallocate($dst_image, $red, $grn, $blu);

	imagefill($dst_image, 0, 0, $dst_color);

	$src_x = 0;
	$src_y = 0;

	// center logo 

	$dst_x = (int)($rw/2 - $dst_width/2);
	$dst_y = (int)($rh/2 - $dst_height/2);

	imagecopyresampled(
	    $dst_image,
	    $src_image,
	    $dst_x,
	    $dst_y,
	    $src_x,
	    $src_y,
	    $dst_width,
	    $dst_height,
	    $src_width,
	    $src_height
	);

	imagepng($dst_image, "$outpath/{$rw}x{$rh}.png", 9); // min filesize

 	imagedestroy($dst_image);

	$axis = ($rw < $rh) ? 'portrait' : 'landscape';

	return "<link rel=\"apple-touch-startup-image\" media=\"screen and (device-width: {$cw}px) and (device-height: {$ch}px) and (-webkit-device-pixel-ratio: $dpr) and (orientation: $axis)\" href=\"$location/{$rw}x{$rh}.png\">";
}


// convert html color code to rgb
//
list($red, $grn, $blu) = array_map(static fn ($value) => hexdec($value), str_split($bg_color,2));

list($src_width, $src_height) = getimagesize($logo_file);

$aspect = $src_width / $src_height; 

$src_image = @imagecreatefrompng($logo_file);

for($i=0; $i<count($splash_size); $i++) {

	list($cw, $ch, $dpr, $dw, $dh, $id) = $splash_size[$i];

	$rw = $dw; $rh = $dh; $meta[] = emit_splash();

	if($enable_landscape) {
		$rw = $dh; $rh = $dw; $meta[] = emit_splash();
	}
}

imagedestroy($src_image);	

file_put_contents("{$document_root}/$location/meta.txt",implode("\r\n",$meta));

print "*** Done. ***\r\n\r\n";

?>