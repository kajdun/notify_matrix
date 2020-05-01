<?php
class notify_matrix {
	function __CONSTRUCT($options = false) {
		$this->_format = "plain";
		$this->_displayName = "Webhook";
		$this->_avatarUrl = "https://www.viwa.de/assets/img/v.png";
		$this->_text = false;
		$this->_webhookUrl = false;

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
	}

	function setText($text) {
		$this->_text = $text;
		return $this;
	}

	function setFormat($format) {
		if(!in_array($format, array("html", "plain"))) throw new Exception('Wrong format');
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

	function send() {
		if($this->getWebhookUrl()==false) throw new Exception("WebhookUrl missing", 1);

		$msg['text'] = $this->getText();
		$msg['format'] = $this->getFormat();
		$msg['displayName'] = $this->getDisplayName();
		$msg['avatarUrl'] = $this->getAvatarUrl();

		$json_msg = json_encode($msg);
		print_r($json_msg);
	}

}
?>
