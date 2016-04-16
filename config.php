<?php
/*
 * Stack Overflow ImageFlair Creation Script - Configuration File
 * Copyright (C) Steven Robbins 2009
 *
 * All fonts must be specified as TTF
 *
 * See the following URL for a freely downloadable version of Arial:
 * http://sourceforge.net/projects/corefonts/files/the%20fonts/arial32.exe/download
 */
?>
<?php

// Debug mode disables sending the actual image
// Useful for seeing any output, warnings or errors
// from the PHP script when testing
$imageflair_debug = false;

// Allow querystring parameters?
// e.g. thisScript.php?userid=<userid>
//
// Set to false if you don't want other people to use your script
// with their details :-)
$imageflair_allow_parameters = true;

// Time to cache the image, in minutes
$imageflair_cache_time = 30;

// Which "Mode" to use
// Values are:
//  so - Stack Overflow
//  meta - Meta Stack OVerflow
//  sf - Server Fault
//  su - Super User
//
// Can be overridden if imageflair_allow_parameters is set to true
$imageflair_mode = 'so';

// Stack Overflow User Id
// Can be overridden if imageflair_allow_parameters is set to true
$imageflair_userid = 26507;

// Background image PNG
$imageflair_background = 'background1.png';

// Gravatar location and size
// Specify size as 0 to use the original size
// Specify negative location for offset from right/bottom
$imageflair_gravatar_x = 5;
$imageflair_gravatar_y = 5;
$imageflair_gravatar_width = 53;
$imageflair_gravatar_height = 53;

// Display name font, size, colour and location
// Specify negative location for offset from right/bottom
$imageflair_name_font = 'Arial.TTF';
$imageflair_name_font_size = 10;
$imageflair_name_r = 255;
$imageflair_name_g = 255;
$imageflair_name_b = 0;
$imageflair_name_x = -7;
$imageflair_name_y = 15;

// Rep font, size, colour and location
// Specify negative location for offset from right/bottom
$imageflair_rep_font = 'Arial.TTF';
$imageflair_rep_font_size = 10;
$imageflair_rep_r = 230;
$imageflair_rep_g = 230;
$imageflair_rep_b = 230;
$imageflair_rep_x = -7;
$imageflair_rep_y = 35;

// Rep "logo" filename, margin and mode
// Mode can be one of:
//   before - adds as a prefix to the rep text
//   after  - adds as a suffix
//   none   - do not add
//
// If you place [MODE] in the filename it will be replaced by the current
// mode (see mode config for more information)
$imageflair_rep_logo = 'icon-[MODE].png';
$imageflair_rep_mode = 'before';
$imageflair_rep_margin = 4;

// Badge font, size, colour, position and margin
// Specify negative location for offset from right/bottom
$imageflair_badges_font = 'Arial.TTF';
$imageflair_badges_font_size = 10;
$imageflair_badges_r = 0;
$imageflair_badges_g = 0;
$imageflair_badges_b = 0;
$imageflair_badges_x = -6;
$imageflair_badges_y = 55;
$imageflair_badges_symbol_margin = 2;
$imageflair_badges_type_margin = 8;
$imageflair_badges_gold_r = 255;
$imageflair_badges_gold_g = 204;
$imageflair_badges_gold_b = 0;
$imageflair_badges_silver_r = 192;
$imageflair_badges_silver_g = 192;
$imageflair_badges_silver_b = 192;
$imageflair_badges_bronze_r = 204;
$imageflair_badges_bronze_g = 153;
$imageflair_badges_bronze_b = 102;
?>