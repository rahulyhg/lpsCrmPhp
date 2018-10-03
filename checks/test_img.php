<?
function get_account_img($account_number_div)
{
	// Set the content-type
	//header('Content-type: image/jpeg');

	// Create the image
	$im = imagecreatetruecolor(400, 18) or die('No se puede Iniciar el nuevo flujo a la imagen GD');
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	//$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 399, 29, $white);
	
	// The text to draw
	$text = $account_number_div;
	// Replace path by your own font path
	$font = 'micrenc.ttf';
	// Add some shadow to the text
	//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

	// Add the text
	imagettftext($im, 17, 0, 8, 14, $black, $font, $text);
	
	// Using imagepng() results in clearer text compared with imagejpeg()
	$path = "images/$account_number_div".".jpg";
	imagejpeg($im,$path);
	imagedestroy($im);
}
get_account_img("0698897998985");
?>