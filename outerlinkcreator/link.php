<?php

session_start();
require_once("../../../wp-config.php");
  // change the following paths if necessary get_header('home');
get_header();
get_template_part(THEME_INCLUDES.'top');
$outer = get_option('outer_option_name');
echo '<center><h4>'.$outer['delay_message'].'</h4></center>
<meta http-equiv="refresh" content="'.$outer['delay_in_second'].'; url='.$_GET['link'].'">';
get_footer();
?>

