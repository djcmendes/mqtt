<?php

// Include libraries
require './vendor/autoload.php';
require './config.php';
require './SimpleLogger.php';

use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// Create an instance of a PSR-3 compliant logger. For this example, we will also use the logger to log exceptions.
echo '.......start ' . __FILE__ . PHP_EOL;

try {
    // Create a new instance of an MQTT client and configure it to use the shared broker host and port.
    $client = new MqttClient(MQTT_BROKER_HOST, MQTT_BROKER_PORT, 'test-subscriber', MqttClient::MQTT_3_1, null, null);


    // Connect to the broker without specific connection settings but with a clean session.
    $client->connect(null, true);

    // Subscribe to the topic 'foo/bar/baz' using QoS 2.
    $client->subscribe('foo/bar/baz', function (string $topic, string $message, bool $retained) use ($client) {
        echo 'info: received' . PHP_EOL;
        echo 'topic:' . $topic . PHP_EOL;
        echo 'typeOfMessage:' . $retained ? 'retained message' : 'message' . PHP_EOL;

        // After receiving the first message on the subscribed topic, we want the client to stop listening for messages.
        $client->interrupt();
    }, MqttClient::QOS_EXACTLY_ONCE);

    // Since subscribing requires to wait for messages, we need to start the client loop which takes care of receiving,
    // parsing and delivering messages to the registered callbacks. The loop will run indefinitely, until a message
    // is received, which will interrupt the loop.
    $client->loop(true, TRUE, 10000);

    // Gracefully terminate the connection to the broker.
    echo 'disconnect' . PHP_EOL;
    $client->disconnect();
} catch (MqttClientException $e) {
    // MqttClientException is the base exception of all exceptions in the library. Catching it will catch all MQTT related exceptions.
    echo 'Subscribing to a topic using QoS 2 failed. An exception occurred.' . print_r($e, true) . PHP_EOL;
}
echo '.......end ' . __FILE__ . PHP_EOL;
