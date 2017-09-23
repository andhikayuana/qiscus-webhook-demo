<?php 

use GuzzleHttp\Client;

class PostCommentHandler {

    private $baseUrl;
    private $qiscusSecretKey;
    private $qiscusComment;
    private $client;

    public function __construnct($baseUrl, $qiscusSecretKey) {
        $this->baseUrl = $baseUrl;
        $this->qiscusSecretKey = $qiscusSecretKey;
    }

    private function createHeaders() {
        $this->headers = [
            'QISCUS_SDK_SECRET' => QISCUS_SDK_SECRET,
            'Content-Type' => 'application/json'
        ];
    }

    private function createClient() {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => BASE_URL
        ]);
    }

    private function request($body = []) {
        $this->createHeaders();
        $this->createClient();
        $response = $this->client->request('POST', '/api/v2/rest/post_comment', [
            'headers' => $this->headers,
            'json' => $body
        ]);

        return $response;
    }

    private function getPayloadFromPostComment() {
        return json_decode(file_get_contents("php://input"), true);
    }

    public function getQiscusComment() {
        return $this->qiscusComment;
    }

    public function run() {
        $this->qiscusComment = $this->getPayloadFromPostComment();

        file_put_contents('log-comment.txt', json_encode($this->getQiscusComment(), JSON_PRETTY_PRINT));
    }

    public function getQiscusCommentType() {
        return $this->qiscusComment['type'];
    }

    public function getQiscusCommentPayload() {
        return $this->qiscusComment['payload'];
    }

    public function getQiscusCommentFrom() {
        return $this->getQiscusCommentPayload()['from'];
    }

    public function getQiscusCommentRoom() {
        return $this->getQiscusCommentPayload()['room'];
    }

    public function getQiscusCommentMessage() {
        return $this->getQiscusCommentPayload()['message'];
    }

    public function makeAnswerNotFound() {
        return $this->request([
            'sender_email' => 'ismail@domain.com',
            'room_id' => '37537',
            'type' => 'buttons',
            'payload' => [
                'text' => 'Aduh ga tau nih, Silakan klik menu start di bawah ya :D',
                'buttons' => [
                    [
                        'label' => 'start',
                        'type' => 'postback',
                        'payload' => [
                            'url' => 'http://api.anu.com',
                            'method' => 'get',
                            'payload' => null
                        ],
                    ]
                ]
            ]
            // 'message' => 'Aduh ga tau nih :D'
        ]);
    }

    public function makeTest() {
        return $this->request([
            'sender_email' => 'ismail@domain.com',
            'room_id' => '37537',
            'type' => 'buttons',
            'payload' => [
                'text' => 'just for testing',
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
                        'label' => 'pembelian',
                        'type' => 'postback',
                        'payload' => [
                            'url' => 'http://api.anu.com',
                            'method' => 'get',
                            'payload' => null
                        ]
                    ]
        
                ]
        
            ]
        ]);
    }

    public function answerGetStarted() {
        $message = "Selamat datang di Lifestyle Insurance.

        Di sini kamu akan diperkenalkan dengan Asuransi Proteksi Income :D

        Jika kamu memilih menu \"Simulasi\".

        Apabila kamu memilih menu \"Pembelian\". 
        
        Kamu akan langsung diarahkan untuk mencari agent yang terdekat di kotamu :D";

        return $this->request([
            'sender_email' => TARGET_EMAIL,
            'room_id' => ROOM_ID,
            'type' => 'buttons',
            'payload' => [
                'text' => $message,
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
                        'label' => 'pembelian',
                        'type' => 'postback',
                        'payload' => [
                            'url' => 'http://api.anu.com',
                            'method' => 'get',
                            'payload' => null
                        ]
                    ]
        
                ]
        
            ]
        ]);
    }

    public function answerSimulation() {
        // todo
    }

    public function answerBuy() {
        // todo
    }

    public function answerListAgent() {
        // todo
    }

    public function answerInputSalary() {
        // todo
    }

}