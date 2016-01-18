<?php
	/**
	 * @brief Utilities
	 *
	 * ## Overview
	 * Provides interfaces for making external requests and handling common
	 * app procedures.
	 *
	 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	 * @date 12/25/2015
	 */

	abstract class RFPluginOPConnector{
	
		static private $appid;
		static private $key;

		static public function FindData($data){
			return self::Request($data, 'search');
		}
	
		static public function AddTag($data){
			return self::Request($data, 'add_tag');
		}
	
		static public function UpdateContactField($data){
			return self::Request($data, 'update');
		}
		
		static private function GetCredentials(){
			if (empty(self::$appid) || empty(self::$key)){
				$data = json_decode( file_get_contents( dirname( __FILE__ ) . '/data/config.json'), true );
				
				self::$appid = $data['op']['appid'];
				self::$key = $data['op']['key'];
			}
			
			return true;
		}
	
		static public function Request($data, $reqType){
			$data = urlencode(urlencode($data));
			
			self::GetCredentials();

			$postargs = 'appid='.self::$appid.'&key='.self::$key.'&reqType='.$reqType.'&data='.$data;
		
			$request = 'http://api.ontraport.com/cdata.php';
		
			$session = curl_init($request);
			curl_setopt ($session, CURLOPT_POST, true);
			curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
			curl_setopt ($session, CURLOPT_HEADER, false);
			curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($session);
			curl_close($session);
	
			return $response;
		}
	}

	abstract class RFPluginHDTIConnector{

		static public function Request($postargs){		
		
			$request = 'http://regalfinancialnyc.net/prospect_signup.php';
		
			$session = curl_init($request);
			curl_setopt ($session, CURLOPT_POST, true);
			curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
			curl_setopt ($session, CURLOPT_HEADER, false);
			curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($session);
			curl_close($session);
	
			return $response;
		}
	}
