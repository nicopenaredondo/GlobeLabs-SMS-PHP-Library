<?php
require_once 'GlobeLabsSMS.php';

$uName = 'Your uName';
$uPin = 'Your uPin';

$sms = new GlobeLabsSMS( $uName, $uPin );

$config = $sms->config(); //Get your config

$error_codes = $sms->error_codes(); //Get defined error codes

$receive = $sms->receive(); //Get received SMS content

$send = $sms->send( 'Recipients Globe No. here', 'Your message will be here' ); //Send an SMS
#END OF PHP FILE