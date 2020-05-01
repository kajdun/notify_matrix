# notify_matrix.class.php

PHP class for sending messages to Slack-compatible webhooks for Matrix. [Slack-compatible webhooks](https://github.com/turt2live/matrix-appservice-webhooks)

# Usage

```
$options = array("format" => "html", 
		 "displayname" => "SomeOne", 
		 "text" => "Have a wonderful day!",
		 "webhookurl" => "https://example.com/api/v1/matrix/hook/123456");

try{
	$notifier = new notify_matrix($options);
	$notifier->send();
} catch (Exception $e) {
	echo $e->getMessage();
}
```

or

```
try{
	$notifier = new notify_matrix();
	$notifier->setText("some Text")
	 	 ->setFormat("plain")
		 ->setDisplayName("Some Name")
		 ->setWebhookUrl("https://URL_TO_WEBHOOK")
		 ->send();

} catch (Exception $e) {
	echo $e->getMessage();
}
```

