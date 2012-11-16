<?php
/**
 * GlobeLabs-SMS PHP Library
 * 
 * A simple PHP library for GlobeLabs SMS API. 
 * It allows you to integrate SMS messaging 
 * into your applications using GlobeLabs API gateway.
 * 
 * @see https://www.globelabs.com.ph/api/Pages/Welcome.aspx?WId=7
 * @filesource  https://github.com/omarusman/GlobeLabs-SMS-PHP-Library
 * @author https://github.com/omarusman
 * @license http://creativecommons.org/licenses/by/3.0/
 */

class GlobeLabsSMS{
	
	private $config;
	private $error_codes;
	
	/**
	 * GlobeLabsSMS PHP library constructor
	 * 
	 * @param String $uName
	 * @param String $uPin
	 */
	public function __construct( $uName = '', $uPin = '' )
	{
		//Set config
		$this->config = array(
			'gateway'		=> 'http://iplaypen.globelabs.com.ph:1881/axis2/services/Platform',
			'uName'			=> $uName,
			'uPin'			=> $uPin,
			'MSISDN'		=> '',
			'messageString'	=> '',
			'Display'		=> '1',
			'udh'			=> '',
			'mwi'			=> '',
			'coding'		=> '0',
		);
		
		//Define error codes
		$this->error_codes = array(
			'301' => 'User is not allowed to access this service',
			'302' => 'User exceeded daily cap',
			'303' => 'Invalid message length',
			'304' => 'Maximum Number of simultaneous connections reached',
			'305' => 'Invalid login credentials',
			'401' => 'SMS sending failed',
			'402' => 'MMS sending failed',
			'501' => 'Invalid target MSISDN',
			'502' => 'Invalid display type',
			'503' => 'Invalid MWI',
			'506' => 'Badly formed XML in SOAP request',
			'504' => 'Invalid Coding',
			'505' => 'Empty value given in required argument',
			'507' => 'Argument given too large',
			'201' => 'SMS accepted for delivery',
			'202' => 'MMS Accepted for delivery',
		);
	}
	
	/**
	 * Get current config
	 * 
	 * @return array
	 */
	public function config()
	{
		return $this->config;
	}
	
	/**
	 * Get list of defined error codes
	 * 
	 * @return array
	 */
	public function error_codes()
	{
		return $this->error_codes;
	}
	/**
	 * Get received SMS message from GlobeLabs server
	 * 
	 * @return array 
	 */
	public function receive()
	{
		//Set initial result
		$result = false;
		
		//Get POST data from GlobeLabs
		if( $_SERVER['REQUEST_METHOD'] == 'POST' )
		{
		
			$post = file_get_contents("php://input");
		
			//Parse data
			@$output = simplexml_load_string( $post );
			
			if( $output )
			{
				foreach( $output->param as $param )
				{
					$key = (String) $param->name;
					$value = (String) $param->value;
				
					$result[ $key ] = $value;
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Send an SMS
	 * @param $to
	 * @param $message
	 * 
	 * @return array
	 */
	public function send( $to = '', $message = '' )
	{
		//Set GlobeLabs POST data
		$data = $this->config;

		//Get GlobeLabs sendSMS gateway
		$url = $data['gateway'] . '/sendSMS';
		
		//Set recipient and message
		$data['messageString'] = $message;
		$data['MSISDN'] = $to;

		//Remove gateway index from data
		unset( $data['gateway'] );
		
		//Build query
		$data = urldecode( http_build_query( $data ) );

		//Set header
		$header = array(
			'Content-Type: application/x-www-form-urlencoded',
			'User-Agent: GlobeLabs-SMS PHP (tapto.mobi)',
			'Connection: keep-alive',
			'Accept: */*',
			'Origin: GlobeLabs-SMS PHP: //tapto.mobi',
			'Content-Length: ' . strlen( $data ),
		);
		
		//Send SMS
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $header );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

		//Catch response
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		
		curl_close($ch);

		//Set initial result (Failed to Send SMS)
		$result = array(
			'code' => '401',
			'error' => $this->error_codes['401']
		);
		
		//If reached GlobeLabs server
		if( isset( $info['http_code'] ) AND $info['http_code'] !== 404 )
		{
		   //Check and parse response
		   preg_match('/<ns:return>([0-9]+)<\/ns:return>/', $output, $matches);
		   $code = $matches[1];
		   
		   //Set result
		   $result = array(
		   		'code' => $code, 
		   		'error' => $this->error_codes[$code]
		   );
		} 
		
		return $result;
	}
}
#END OF PHP FILE