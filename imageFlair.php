<?php
/*
 * Stack Overflow ImageFlair Creation Script v1.0.1
 * Copyright (C) Steven Robbins 2009
 *
 * Please take a look in config.php for configuration options.
 *
 * See the following URL for a freely downloadable version of Arial:
 * http://sourceforge.net/projects/corefonts/files/the%20fonts/arial32.exe/download
 */
?>
<?php
require("config.php");
require("functions.php");

// Check to see if cache is valid
if (!$imageflair_debug) {
  $cache_file = get_cache_filename('imageFlair'.get_mode($imageflair_mode, $imageflair_allow_parameters).get_stackoverflow_userid($imageflair_userid, $imageflair_allow_parameters));
  $timedif = @(time() - filemtime($cache_file));
  if (file_exists($cache_file) && $timedif < ($imageflair_cache_time * MINUTES)) {
    $cached = imagecreatefrompng($cache_file);
    header('Content-type: image/png');
    imagepng($cached);
    exit;
  }
}

// Get Stack Overflow values
$so_variables = get_stackoverflow_statistics(get_stackoverflow_userid($imageflair_userid, $imageflair_allow_parameters), get_mode($imageflair_mode, $imageflair_allow_parameters));

// Create background
$background_size=list($background_width, $background_height, $background_format) = getimagesize($imageflair_background);
switch ($background_format)
{
  case 1: 
    $background_image = imagecreatefromgif($imageflair_background); 
    break;
  case 2: 
    $background_image = imagecreatefromjpeg($imageflair_background);  
    break;
  case 3: 
    $background_image = imagecreatefrompng($imageflair_background); 
    break;
  default: 
    $background_image = imagecreatetruecolor( 250, 64 );
    break;
}

// Load gravatar
$gravatar_url=get_gravatar_url($so_variables);
$gravatar_size=list($gravatar_width, $gravatar_height, $gravatar_format) = getimagesize($gravatar_url);
switch ($gravatar_format)
{
  case 1: 
    $gravatar_image = imagecreatefromgif($gravatar_url); 
    break;
  case 2: 
    $gravatar_image = imagecreatefromjpeg($gravatar_url);  
    break;
  case 3: 
    $gravatar_image = imagecreatefrompng($gravatar_url); 
    break;
  default: 
    $gravatar_image = imagecreatetruecolor( 48, 48 );
    break;
}

// Get destination width/height - or defaults of 0
$gravatar_dest_width = ($imageflair_gravatar_width == 0) ? $gravatar_width : $imageflair_gravatar_width;
$gravatar_dest_height = ($imageflair_gravatar_height == 0) ? $gravatar_height : $imageflair_gravatar_height;

// Calculate paste coordinates
$gravatar_dest_x = get_actual_x($imageflair_gravatar_x, $gravatar_dest_width, $background_width);
$gravatar_dest_y = get_actual_y($imageflair_gravatar_y, $gravatar_dest_height, $background_height);

// Paste gravatar
imagecopyresampled($background_image,$gravatar_image,$gravatar_dest_x,$gravatar_dest_y,0,0, $gravatar_dest_width,$gravatar_dest_height,$gravatar_width,$gravatar_height);

// Add display name
$so_displayname_colour = imagecolorallocate($background_image, $imageflair_name_r, $imageflair_name_g, $imageflair_name_b);
$so_displayname_bounds = imagettfbbox($imageflair_name_font_size, 0, $imageflair_name_font, get_stackoverflow_displayname($so_variables));
$so_displayname_width = $so_displayname_bounds[2] - $so_displayname_bounds[6];
$so_displayname_height = $so_displayname_bounds[3] - $so_displayname_bounds[7];
$so_displayname_x = get_actual_x($imageflair_name_x, $so_displayname_width, $background_width);
$so_displayname_y = get_actual_y($imageflair_name_y, $so_displayname_height, $background_height);
imagettftext($background_image, $imageflair_name_font_size, 0, $so_displayname_x, $so_displayname_y, $so_displayname_colour, $imageflair_name_font, get_stackoverflow_displayname($so_variables));

// Add rep text - calculate logo x position if required
$so_rep_colour = imagecolorallocate($background_image, $imageflair_rep_r, $imageflair_rep_g, $imageflair_rep_b);
$so_rep_bounds = imagettfbbox($imageflair_rep_font_size, 0, $imageflair_rep_font, get_stackoverflow_rep($so_variables));
$so_rep_width = $so_rep_bounds[2] - $so_rep_bounds[6];
$so_rep_height = $so_rep_bounds[3] - $so_rep_bounds[7];
$so_rep_x = get_actual_x($imageflair_rep_x, $so_rep_bounds[2], $background_width);
$so_rep_y = get_actual_y($imageflair_rep_y, $so_rep_bounds[3], $background_height);

$so_rep_logo_filename = get_rep_logo($imageflair_rep_logo, get_mode($imageflair_mode, $imageflair_allow_parameters));

switch ($imageflair_rep_mode)
{
  case 'before':
    $so_rep_logo_size=list($so_rep_logo_width, $so_rep_logo_height, $so_rep_logo_format) = getimagesize($so_rep_logo_filename);
    $so_rep_actual_bounds = imagettftext($background_image, $imageflair_rep_font_size, 0, $so_rep_x, $so_rep_y, $so_rep_colour, $imageflair_rep_font, get_stackoverflow_rep($so_variables));
    $so_rep_logo_x = $so_rep_actual_bounds[6] - $so_rep_logo_width - $imageflair_rep_margin;
    break;
  case 'after':
    $so_rep_logo_size=list($so_rep_logo_width, $so_rep_logo_height, $so_rep_logo_format) = getimagesize($so_rep_logo_filename);
    $so_rep_actual_bounds = imagettftext($background_image, $imageflair_rep_font_size, 0, $so_rep_x-$so_rep_logo_width-$imageflair_rep_margin, $so_rep_y, $so_rep_colour, $imageflair_rep_font, get_stackoverflow_rep($so_variables));
    $so_rep_logo_x = $so_rep_actual_bounds[2] + $imageflair_rep_margin;
    break;
  case 'none':
  default:
    imagettftext($background_image, $imageflair_rep_font_size, 0, $so_rep_x, $so_rep_y, $so_rep_colour, $imageflair_rep_font, get_stackoverflow_rep($so_variables));
    break;
}

// Add the rep icon if required
if (($imageflair_rep_mode == 'before') || ($imageflair_rep_mode == 'after'))
{
  switch ($so_rep_logo_format)
  {
    case 1: 
      $so_rep_logo = imagecreatefromgif($so_rep_logo_filename); 
      break;
    case 2: 
      $so_rep_logo = imagecreatefromjpeg($so_rep_logo_filename);  
      break;
    case 3: 
      $so_rep_logo = imagecreatefrompng($so_rep_logo_filename); 
      break;
    default: 
      $so_rep_logo = imagecreatetruecolor( 20,20 );
      break;
  }

  // Calculate Y coordinate depending on the 2 heights
  if ($so_rep_logo_height == $so_rep_height) {
    $so_rep_logo_y = $so_rep_actual_bounds[5];
  } else {
    $so_rep_logo_y = $so_rep_actual_bounds[5] + ($so_rep_height - $so_rep_logo_height)/2;
  }
  
  imagecopyresampled($background_image,$so_rep_logo,$so_rep_logo_x,$so_rep_logo_y,0,0, $so_rep_logo_width,$so_rep_logo_height,$so_rep_logo_width,$so_rep_logo_height);
}

// Add the badge counts - this is going to be a pain in the bum :-)
$so_badge_colour = imagecolorallocate($background_image, $imageflair_rep_r, $imageflair_rep_g, $imageflair_rep_b);
$so_gold_colour = imagecolorallocate($background_image, $imageflair_badges_gold_r, $imageflair_badges_gold_g, $imageflair_badges_gold_b);
$so_silver_colour = imagecolorallocate($background_image, $imageflair_badges_silver_r, $imageflair_badges_silver_g, $imageflair_badges_silver_b);
$so_bronze_colour = imagecolorallocate($background_image, $imageflair_badges_bronze_r, $imageflair_badges_bronze_g, $imageflair_badges_bronze_b);

$so_gold = get_stackoverflow_gold($so_variables);
$so_silver = get_stackoverflow_silver($so_variables);
$so_bronze = get_stackoverflow_bronze($so_variables);

$so_badge_current_x = get_actual_x($imageflair_badges_x, 0, $background_width);
if ($so_bronze > 0)
{
  $so_bronze_bounds = imagettfbbox($imageflair_badges_font_size, 0, $imageflair_badges_font, $so_bronze);
  $so_bronze_width = $so_bronze_bounds[2] - $so_bronze_bounds[6];

  $so_bronze_height = $so_bronze_bounds[3] - $so_bronze_bounds[7];

  $so_badge_current_y = get_actual_y($imageflair_badges_y, $so_bronze_height, $background_height);

  imagettftext($background_image, $imageflair_badges_font_size, 0, $so_badge_current_x - $so_bronze_width, $so_badge_current_y, $so_badge_colour, $imageflair_badges_font, $so_bronze);
  $so_badge_current_x -= $so_bronze_width;
  $so_badge_current_x -= $imageflair_badges_symbol_margin;
  
  $so_badge_current_x = add_badge_symbol($background_image, $imageflair_badges_font_size, $imageflair_badges_font, $so_badge_current_x, $so_badge_current_y, $so_bronze_colour);
  $so_badge_current_x -= $imageflair_badges_type_margin;
}

if ($so_silver > 0)
{
  $so_silver_bounds = imagettfbbox($imageflair_badges_font_size, 0, $imageflair_badges_font, $so_silver);
  $so_silver_width = $so_silver_bounds[2] - $so_silver_bounds[6];
  $so_silver_height = $so_silver_bounds[3] - $so_silver_bounds[7];

  $so_badge_current_y = get_actual_y($imageflair_badges_y, $so_silver_height, $background_height);

  imagettftext($background_image, $imageflair_badges_font_size, 0, $so_badge_current_x - $so_silver_width, $so_badge_current_y, $so_badge_colour, $imageflair_badges_font, $so_silver);
  $so_badge_current_x -= $so_silver_width;
  $so_badge_current_x -= $imageflair_badges_symbol_margin;
  
  $so_badge_current_x = add_badge_symbol($background_image, $imageflair_badges_font_size, $imageflair_badges_font, $so_badge_current_x, $so_badge_current_y, $so_silver_colour);
  $so_badge_current_x -= $imageflair_badges_type_margin;
}

if ($so_gold > 0)
{
  $so_gold_bounds = imagettfbbox($imageflair_badges_font_size, 0, $imageflair_badges_font, $so_gold);
  $so_gold_width = $so_gold_bounds[2] - $so_gold_bounds[6];
  $so_gold_height = $so_gold_bounds[3] - $so_gold_bounds[7];

  $so_badge_current_y = get_actual_y($imageflair_badges_y, $so_gold_height, $background_height);

  imagettftext($background_image, $imageflair_badges_font_size, 0, $so_badge_current_x - $so_gold_width, $so_badge_current_y, $so_badge_colour, $imageflair_badges_font, $so_gold);
  $so_badge_current_x -= $so_gold_width;
  $so_badge_current_x -= $imageflair_badges_symbol_margin;
  
  $so_badge_current_x = add_badge_symbol($background_image, $imageflair_badges_font_size, $imageflair_badges_font, $so_badge_current_x, $so_badge_current_y, $so_gold_colour);
  $so_badge_current_x -= $imageflair_badges_type_margin;
}

// Output image
if (!$imageflair_debug) {
  header('Content-type: image/png');
  imagepng($background_image);
  imagepng($background_image, $cache_file);
}

// Tidy up
imagecolordeallocate($background_image, $so_bronze_colour);
imagecolordeallocate($background_image, $so_silver_colour);
imagecolordeallocate($background_image, $so_gold_colour);
imagecolordeallocate($background_image, $so_badge_colour);
imagecolordeallocate($background_image, $so_rep_colour);
imagecolordeallocate($background_image, $so_displayname_colour);
imagedestroy($so_rep_logo);
imagedestroy($gravatar_image);
imagedestroy($background_image);
?>