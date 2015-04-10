<?php 

/***
 * @Copyright: 	2015, Tuongee 
 * @website: 	http://tuongee.com
 * @Support Email: tuongee.info@gmail.com
 * 
 * 
 * @Documentation: https://github.com/tuongee/tuongee-php-sdk
 * 
 * Tuongee php sdk library
 */
class Tuongee{

	// The header message that is echoed back to your customer 
	// NB: Can be empty if you don't want to send any reply back to user 
	private $_message = null;

	// The menu which will follow the header message above 
	// NB: Can be empty 
	private $_menu = array();

	// Current application api key and secret key obtained from the applications dashboard. 
	// If you dont have, go to http://tuongee.com to create one 
	private $_api_key = null;
	private $_api_secret = null;

	// api version details 
	// DONT EDIT:: If you do, no response will hit your customer :)
	private $_api_version = '1';

	// Set this to true so as to see all the errors and exceptions 
	// printed back to your development environment
	// 
	// Note: Set this to false on production
	public static $debug = False;
	
	function __construct( $api_key, $api_secret ){
		$this->_api_key = $api_key;
		$this->_api_secret = $api_secret;

		self::showDebug('Tuongee application initialized');
	}

	public function setHeaderMessage( $message ){
		$this->_message = $message;
	}

	/**
	 * Set the response menu that is to be sent back to the whatsapp user
	 * 
	 * @param $menu_array - an associative array of menu code and the description in the form 
	 * array( "code": "code_here", "message": "menu tag here") e.g. array("code": 1, "message": "Check order status")
	 * 
	 * @return boolean  - True if the menu was created otherwise we skip 
	 */
	public function setMenu( $menu = array() ){
		self::showDebug("Adding response menu");
		if ( is_array( $menu )) {
			foreach ($menu as $single_menu ) {
				if ( array_key_exists("code", $single_menu) and array_key_exists("message", $single_menu) ) {
					$this->_menu[] = $single_menu;
				}
			}
		}else{
			self::showDebug("Error in your menu format..Check ");
		}
	}

	/**
	 * Validate the data supplied and echo back response to user 
	 */
	public function display( ){
		$valid = $this->validateData();
		if ( $valid ) {
			if ( ! self::$debug ) {
				header('Content-type: application/xml');
			}else{
				header('Content-type: text/plain');
			}
			echo $this->format();

		}else{
			self::showDebug("Your response data is invalid");
		}
	}

	private function validateData( ){
		self::showDebug("Validating data");
		return true;
	}

	public function format(){
		$string = '<?xml version="1.0" encoding="UTF-8"?>';
		$string.= '<tuongee>';
		$string.= '<application>';
		if( !empty( $this->_api_version )){
			$string.='<version>'.$this->_api_version.'</version>';
		}
		if( !empty( $this->_api_key )){
			$string.='<api_key>'.$this->_api_key.'</api_key>';
		}
		if( !empty( $this->_api_secret )){
			$string.='<api_secret>'.$this->_api_secret.'</api_secret>';
		}
		$string.='</application>';

		$string.='<header>';
		if( !empty( $this->_message )){
			$string.='<message>'.$this->_message.'</message>';
		}
		$string.='</header>';

		$string.= '<menus>';
		foreach ($this->_menu as $single_menu ) {
			$string.="<menu>";
			$string.="<code>".$single_menu["code"]."</code>";
			$string.="<message>".$single_menu["message"]."</message>";
			$string.="</menu>";
		}
		$string.= '</menus>';
		
		$string.= '</tuongee>';
		return $string;
	}

	public static function setDebug( $state ){
		if( is_bool( $state) ){
			self::$debug = $state;
			self::showDebug('Debug changed to '.self::$debug );
		}
	}

	public static function showDebug( $message=null ){
		if( ! empty( $message ) && self::$debug ){
			echo "{$message} <br/>";
		}
	}
}

?>