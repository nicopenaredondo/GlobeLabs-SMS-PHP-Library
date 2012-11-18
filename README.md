GlobeLabs-SMS PHP Library
=============

A simple PHP library for GlobeLabs SMS API. 

It allows you to integrate SMS messaging into your applications using GlobeLabs API gateway.

Requirements
-------

> + PHP 5+
> + cURL

Installation
-------

    <?php require_once 'GlobeLabsSMS.php' ?>


Initialize
-------

Create an SMS instance.

    <?php $sms = new GlobeLabsSMS( $uName, $uPin );?>

This requires your uName and uPin which is emailed to you by GlobeLabs during your request to access their API.


Send SMS
-------

Send an SMS to a specific Globe cellphone no.

    <?php $result = $sms->send( '09171234567', 'Hello World' );?>

This returns a $result:

    array( 
      'code'    => '201',
      'error'   => 'SMS accepted for delivery'
    )

This requires a valid and listed Globe cellphone no. and your message.

You can list a valid Globe cellphone number [here](https://202.126.34.119:1888/login.aspx).


Receive an SMS
-------

Receive an SMS posted by GlobeLabs.

    <?php $result = $sms->receive();?>

This returns a $result:

    array( 
      'id'            => '2373123420121116194206',
      'messageType'   => 'SMS',
      'target'        => '23731234',
      'source'        => '09171234567',
      'msg'           => 'Hello World',
      'udh'           => ''
    )

You need to tell GlobeLabs which URL you want them to $_POST the data.

You can update your receiver URL [here](https://202.126.34.119:1888/login.aspx).


Get Config
-------

Get your current GlobeLabs config.

    <?php $result = $sms->config();?>

This returns an array containing your GlobeLabs config


Get Error Codes
-------

Get the defined error codes.

    <?php $result = $sms->error_codes();?>

This returns an array containing all of the valid error codes returned by GlobeLabs.

Refer to [GlobeLabs SMS Error Codes](https://www.globelabs.com.ph/api/Pages/Welcome.aspx?WId=11) for more info.


Weird Stuff
-------
This thing is licensed under: [Attribution 3.0 Unported (CC BY 3.0)](http://creativecommons.org/licenses/by/3.0/)