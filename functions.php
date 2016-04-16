<?php
/*
 * Stack Overflow ImageFlair Creation Script - Helper Functions
 * Copyright (C) Steven Robbins 2009
 */
?>
<?php

// Badge icon character
define('SO_BADGE_ICON', '&#9679;');

// Constants for cache timeout
define("MINUTES", 60);
define("HOURS", MINUTES*60);
define("DAYS", HOURS*24);

// Gets the "mode"
// Either from the config or the querystring
function get_mode($config_mode, $allow_parameters)
{
  if (($allow_parameters != true) || (empty($_GET['mode']))) {
    return $config_mode;
  } else {
    switch ($_GET['mode'])
    {
      case 'so':
      case 'meta':
      case 'sf':
      case 'su':
        return $_GET['mode'];
        break;
      default:
        return $config_mode;
        break;
    }
  }
}

// Gets the SO user id
// Either from the config or from the querystring
function get_stackoverflow_userid($config_userid, $allow_parameters)
{
  if (($allow_parameters == true) && (!empty($_GET['userid']))) {
    $userid = $_GET['userid'];
  } else {
    $userid = $config_userid;
  }
  
	return $userid;
}

// Returns the filename for the cache
//
// Gets the system temp directory and uses the current script dir/name
// plus an optional cache name to generate the filename.
//
// Should always return the same file when given the same cache name
// if the script stays in the same directory :-)
function get_cache_filename($cacheName)
{
	$dir = sys_get_temp_dir();
	$file = trim(__FILE__, '/\\');
	$file = str_replace(';', '.', $file);
	$file = str_replace(':', '.', $file);
	$file = str_replace('/', '.', $file);
	$file = str_replace('\\', ',', $file);
	if (strlen($cacheName) > 0)
    $file = $file.'.'.$cacheName;
	$file = $file.'.cache';

	return $dir.'/'.$file;
}

// Gets the Stack OVerflow statistics for the given user
// Returns an associative array of values
function get_stackoverflow_statistics($userid, $mode)
{
  switch ($mode)
  {
    case 'meta':
      $url = "http://meta.stackoverflow.com/users/flair/".$userid.".json";
      break;
    case 'sf':
      $url = "http://serverfault.com/users/flair/".$userid.".json";
      break;
    case 'su':
      $url = "http://superuser.com/users/flair/".$userid.".json";
      break;
    case 'so':
    default:
      $url = "http://stackoverflow.com/users/flair/".$userid.".json";
      break;
  }

  $raw = file_get_contents($url);
  
  return json_decode($raw, true);
}

// Gets the gravatar image url from a set of StackOVerflow statistics
function get_gravatar_url($statistics)
{
  $regex='<img src="([^"\s]+)';
  if (eregi($regex, $statistics['gravatarHtml'], $output)) {
    return $output[1];
  } else {
    echo 'not found';
    return 'http://www.gravatar.com/avatar';
  }
}

// Gets the user's displayname from a set of StackOVerflow statistics
function get_stackoverflow_displayname($statistics)
{
  return $statistics['displayName'];
}

// Gets the user's rep from a set of StackOVerflow statistics
function get_stackoverflow_rep($statistics)
{
  return $statistics['reputation'];
}

// Gets the user's gold badge count from a set of StackOVerflow statistics
function get_stackoverflow_gold($statistics)
{
  $regex = '<span class="badge1">&#9679;</span><span class="badgecount">([^<>]+)<';
  if (eregi($regex, $statistics['badgeHtml'], $output)) {
    if (is_numeric($output[1])) {
      return $output[1];
    } else {
      return 0;
    }
  } else {
    return 0;
  }
}

// Gets the user's silver badge count from a set of StackOVerflow statistics
function get_stackoverflow_silver($statistics)
{
  $regex = '<span class="badge2">&#9679;</span><span class="badgecount">([^<>]+)<';
  if (eregi($regex, $statistics['badgeHtml'], $output)) {
    if (is_numeric($output[1])) {
      return $output[1];
    } else {
      return 0;
    }
  } else {
    return 0;
  }
}

// Gets the user's bronze badge count from a set of StackOVerflow statistics
function get_stackoverflow_bronze($statistics)
{
  $regex = '<span class="badge3">&#9679;</span><span class="badgecount">([^<>]+)<';
  if (eregi($regex, $statistics['badgeHtml'], $output)) {
    if (is_numeric($output[1])) {
      return $output[1];
    } else {
      return 0;
    }
  } else {
    return 0;
  }
}

// Get actual pixel offset from config offset
// Negative offsets are calculated from the right of the container
function get_actual_x($config_x, $source_width, $container_width)
{
  return ($config_x < 0) ? $container_width+$config_x-$source_width : $config_x;
}

// Get actual pixel offset from config offset
// Negative offsets are calculated from the bottom of the container
function get_actual_y($config_y, $source_height, $container_height)
{
  return ($config_y < 0) ? $container_height+$config_y-$source_height : $config_y;
}

// Expand the logo mode if required
function get_rep_logo($config_logo, $mode)
{
  return (str_replace('[MODE]', $mode, $config_logo));
}

// Draws a badge symbol
function add_badge_symbol($canvas, $font_size, $font, $current_x, $y, $colour)
{
  $badge_bounds = imagettfbbox($font_size, 0, $font, SO_BADGE_ICON);
  $badge_width = $badge_bounds[2] - $badge_bounds[6];
  $badge_height = $badge_bounds[3] - $badge_bounds[7];

  imagettftext($canvas, $font_size, 0, $current_x - $badge_width, $y, $colour, $font, SO_BADGE_ICON);
  $current_x -= $badge_width;
  
  return $current_x;
}
?>