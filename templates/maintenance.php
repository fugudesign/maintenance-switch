<?php

// Displaying this page during the maintenance mode
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
    $protocol = 'HTTP/1.0';    
header( "$protocol 503 Service Unavailable", true, 503 );
header( 'Content-Type: text/html; charset=utf-8' );
header( 'Retry-After: 600' );

// Get the HTML code from plugin options ?>
{{MS_MAINTENANCE_PAGE_HTML}}

<?php 
// end
die(); 

?>