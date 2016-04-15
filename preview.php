<?php

/**
 * Maintenance Switch
 *
 * @author    Fugu <info@fugu.fr>
 * @license   GPL-2.0+
 * @copyright 2015 Fugu
 * @since      1.2.0
 */

// Displaying this page during the maintenance mode
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
    $protocol = 'HTTP/1.0';    
header( 'Content-Type: text/html; charset=utf-8' );


if ( !empty($_POST['preview-code']) ) {
	echo stripslashes( $_POST['preview-code'] );
}

?>