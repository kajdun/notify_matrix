<?php
/**
* Sending Messages to a matrix Webhook
*
* @author	ViWa Invest GmbH
* @copyright (c) 2020, viwa.de
* @see		https://github.com/turt2live/matrix-appservice-webhooks		
*/
class notify_matrix {
	/**
	 * instantiate the class
	 * 
	 * @param array $options
	 * @throws Exception
	 * @return object
	 */	
	function __CONSTRUCT($options = false) {
		$this->_format = "plain";
		$this->_displayName = "Webhook";
		$this->_avatarUrl = "https://www.viwa.de/assets/img/v.png";
		$this->_text = false;
		$this->_webhookUrl = false;
		
		try {
			if (is_array($options)) {
				if(isset($options['format']))
					$this->setFormat($options['format']);

				if(isset($options['displayname']))
					$this->setDisplayName($options['displayname']);	

				if(isset($options['avatarurl']))
					$this->setAvatarUrl($options['avatarurl']);	

				if(isset($options['text']))
					$this->setText($options['text']);	

				if(isset($options['webhookurl']))
					$this->setWebhookUrl($options['webhookurl']);													
			}	
		}	catch (Exception $e) {
			throw new Exception ($e->getMessage());
		}
		return $this;
	}

	/**
	 * sets Text
	 * 
	 * @param string $text
	 * @return object
	 */	
	public function setText($text) {
		$this->_text = $text;
		return $this;
	}

	/**
	 * sets Text
	 * 
	 * @param string $format
	 * @throws Exception
	 * @return object
	 */		
	public function setFormat($format) {
		if(!in_array($format, array("html", "plain"))) throw new Exception('Format must be one of html, plain');
		$this->_format = $format;
		return $this;
	}

	/**
	 * sets displayname
	 * 
	 * @param string $displayname
	 * @return object
	 */	
	public function setDisplayName($displayname) {
		$this->_displayName = $displayname;
		return $this;
	}

	/**
	 * sets Avatar url
	 * 
	 * @param string $url
	 * @throws Exception
	 * @return object
	 */	
	public function setAvatarUrl($url) {
		if (!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception('AvatarUrl must be a URL');
		$this->_avatarUrl = $url;
		return $this;
	}

	/**
	 * sets WebHook url
	 * 
	 * @param string $url
	 * @throws Exception
	 * @return object
	 */	
	public function setWebhookUrl($url) {
		if (!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception('WebhookUrl must be a URL');
		$this->_webhookUrl = $url;		
		return $this;
	}

	/**
	 * Gets Text
	 * 
	 * @param void
	 * @return string
	 */	
	public function getText() {
		return $this->_text;
	}

	/**
	 * Gets Format
	 * 
	 * @param void
	 * @return string
	 */	
	public function getFormat() {
		return $this->_format;
	}

	/**
	 * Gets displayname
	 * 
	 * @param void
	 * @return object
	 */	
	public function getDisplayName() {
		return $this->_displayName;
	}

	/**
	 * Gets Avatar url
	 * 
	 * @param void
	 * @return string
	 */	
	public function getAvatarUrl() {
		return $this->_avatarUrl;
	}

	/**
	 * Gets Webhook url
	 * 
	 * @param void
	 * @return string
	 */	
	public function getWebhookUrl() {
		return $this->_webhookUrl;
	}

	/**
	 * Gets json encoded Message for http post request
	 * 
	 * @param void
	 * @return string
	 */	
	private function _getMessage() {
		$msg['text'] = $this->getText();
		$msg['format'] = $this->getFormat();
		$msg['displayName'] = $this->getDisplayName();
		$msg['avatarUrl'] = $this->getAvatarUrl();

		$json_msg = json_encode($msg);
		return $json_msg;
	}

	/**
	 * Sends request
	 * 
	 * @param void
	 * @throws Exception
	 * @return bool
	 */		
	public function send() {
		if($this->getWebhookUrl()==false) throw new Exception("WebhookUrl missing", 1);
		if($this->getText()==false) throw new Exception("Message missing", 1);
		if(!function_exists('curl_version')) throw new Exception('PHP curl extension is missing');

		/** init curl object and set options */
		$ch = curl_init($this->getWebhookUrl());
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_getMessage());
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		/** execute api call */
		$result = curl_exec($ch);

		/** get http status code of the api call and handle errors*/
		if(!curl_errno($ch)) {
			switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
				case 200:  # OK
					return true;
					break;
				default:
					throw new Exception('Unexpected curl http code: '. $http_code);
			}
		} else {
			throw new Exception(curl_errno($ch));
		}
	}
}
?>