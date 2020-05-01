<?php
/**
* @author	ViWa Invest GmbH
* @datetime	01 May 2020
* @purpose	Sending Messages to a matrix Webhook
* @see		https://github.com/turt2live/matrix-appservice-webhooks		
*/
class notify_matrix {
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

	function setText($text) {
		$this->_text = $text;
		return $this;
	}

	function setFormat($format) {
		if(!in_array($format, array("html", "plain"))) throw new Exception('Format must be one of html, plain');
		$this->_format = $format;
		return $this;
	}

	function setDisplayName($displayname) {
		$this->_displayName = $displayname;
		return $this;
	}

	function setAvatarUrl($url) {
		if (!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception('AvatarUrl must be a URL');
		$this->_avatarUrl = $url;
		return $this;
	}

	function setWebhookUrl($url) {
		if (!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception('WebhookUrl must be a URL');
		$this->_webhookUrl = $url;		
		return $this;
	}

	function getText() {
		return $this->_text;
	}
	function getFormat() {
		return $this->_format;
	}
	function getDisplayName() {
		return $this->_displayName;
	}
	function getAvatarUrl() {
		return $this->_avatarUrl;
	}
	function getWebhookUrl() {
		return $this->_webhookUrl;
	}

	function _getMessage() {
		$msg['text'] = $this->getText();
		$msg['format'] = $this->getFormat();
		$msg['displayName'] = $this->getDisplayName();
		$msg['avatarUrl'] = $this->getAvatarUrl();

		$json_msg = json_encode($msg);
		return $json_msg;
	}
	
	function send() {
		if($this->getWebhookUrl()==false) throw new Exception("WebhookUrl missing", 1);
		if(!function_exists('curl_version')) throw new Exception('php-curl is missing');
		$ch = curl_init($this->getWebhookUrl());
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_getMessage());
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);

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
