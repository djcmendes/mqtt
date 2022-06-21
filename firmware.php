<?php

require './vendor/autoload.php';
require './config.php';
require './SimpleLogger.php';

use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

$time_start = microtime(true);

try {

    $client = new MqttClient(MQTT_BROKER_HOST, MQTT_BROKER_TLS_PORT, 'test-subscriber', MqttClient::MQTT_3_1_1, null, null);

    $connectionSettings = (new ConnectionSettings)->setConnectTimeout(10)
                                                  ->setUseTls(true)
                                                  ->setTlsSelfSignedAllowed(true)
                                                  ->setTlsVerifyPeer(false);

    echo 'try to connect...' . PHP_EOL;
    $client->connect($connectionSettings, true);
    //$client->connect(null, true);
    echo 'client connected!' . PHP_EOL;

    $topic_subscriber = 'AA/GGSP/v1/MYFLIGHT/MYTAGPRO/000000114343/Device_Response';
    $topic_publisher  = 'AA/GGSP/v1/MYFLIGHT/MYTAGPRO/000000114343/Device_Request';
    /*
     * 000000018165
     */

    $client->registerLoopEventHandler(function (MqttClient $client, float $elapsedTime) {
        if ($elapsedTime >= 60) {
            $client->interrupt();
        }
    });

    $published_time = NULL;

    $client->subscribe($topic_subscriber, function (string $topic, string $message, bool $retained) use ($client, $published_time) {
        echo 'info: received' . PHP_EOL;
        echo 'topic:' . $topic . PHP_EOL;
        echo 'message:' . $message . PHP_EOL;
        echo 'typeOfMessage:' . $retained ? 'retained message' : 'message' . PHP_EOL;

        if($published_time !== NULL)
        {
            echo ' received the subescribed message in ' . (microtime(true) - $published_time) . PHP_EOL;
        }

        // After receiving the first message on the subscribed topic, we want the client to stop listening for messages.
        $client->interrupt();
    }, MqttClient::QOS_EXACTLY_ONCE);

    $payload = [
        'appId'        => 'MYFLIGHT',
        'requestRefId' => '08993-30348-28677-00217-03410',
        'requestType'  => 'FIRMWARE',
        'tagType'      => 'MYTAGPRO',
        'tagCode'      => "000000018165",
        'requestTime'  => "2020-11-10T03:24:36Z",
        'requestValue' => []
    ];

    $published_time = (microtime(true) - $time_start);

    $client->publish($topic_publisher, json_encode($payload), MqttClient::QOS_EXACTLY_ONCE);

    $client->loop(true);

    // Gracefully terminate the connection to the broker.
    echo 'disconnect' . PHP_EOL;
    $client->disconnect();
} catch (MqttClientException $e) {
    // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
    echo 'Subscribing to a topic using QoS 2 failed. An exception occurred.' . print_r($e, true) . PHP_EOL;
}


echo 'Terminated ' . (microtime(true) - $time_start) . ' seconds' . PHP_EOL;

/*
{
  "appId": "MYFLIGHT",
  "requestRefId": "08993-30348-28677-00217-03410",
  "requestType": "FIRMWARE",
  "tagType": "MYTAGPRO",
  "tagCode": "000000018165",
  "requestTime": "2020-11-10T03:24:36Z",
  "requestValue": {}
}
*/
