<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use App\PostCommentHandler;

$handler = new PostCommentHandler();

$qiscusComment = file_get_contents("php://input");

$headers = [
    'QISCUS_SDK_SECRET' => 'd01f3afa94edd84c44057dbc48ae54ea',
    'Content-Type' => 'application/json'
];
$body = [
    'sender_email' => 'ismail@domain.com',
    'room_id' => '37537',
    'type' => 'buttons',
    'payload' => [
        'text' => $handler->hello(),
        'buttons' => [
            [
                'label' => 'simulasi',
                'type' => 'postback',
                'payload' => [
                    'url' => 'http://api.anu.com',
                    'method' => 'get',
                    'payload' => null
                ],
            ],
            [
                'label' => 'beli',
                'type' => 'link',
                'payload' => [
                    'url' =>  'http://somewhere.com/button2?id=123'
                ]
            ]

        ]

    ]
];

$client = new GuzzleHttp\Client([
    'base_uri' => 'http://lifestyle-fct27dl8lmo.qiscus.com'
]);
$response = $client->request('POST', '/api/v2/rest/post_comment', [
    'headers' => $headers,
    'json' => $body
]);

echo $response->getBody();