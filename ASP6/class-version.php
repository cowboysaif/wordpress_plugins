<?php
/*********************/
/*                   */
/*  Dezend for PHP5  */
/*         NWS       */
/*      Nulled.WS    */
/*                   */
/*********************/

$ProductID = 28316;
$cURL = curl_init( "http://lma.mass-automation.appspot.com/version?product_id={$ProductID}" );
curl_setopt( $cURL, CURLOPT_HEADER, false );
curl_setopt( $cURL, CURLOPT_RETURNTRANSFER, true );
$version = curl_exec( $cURL );
curl_close( $cURL );
?>
