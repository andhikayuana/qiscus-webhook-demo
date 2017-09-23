<?php 

use GuzzleHttp\Client;

class PostCommentHandler {

    private $baseUrl;
    private $qiscusSecretKey;
    private $qiscusComment;
    private $client;
    private $response;

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
        $this->response = $this->client->request('POST', '/api/v2/rest/post_comment', [
            'headers' => $this->headers,
            'json' => $body
        ]);
    }

    public function run() {
        $this->qiscusComment = file_get_contents("php://input");

        file_put_contents('log-comment.txt', $this->qiscusComment);

        $this->createHeaders();
        $this->createClient();
        $this->request([
            'sender_email' => 'ismail@domain.com',
            'room_id' => '37537',
            'type' => 'buttons',
            'payload' => [
                'text' => $this->answerGetStarted(),
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
        return "Selamat datang di Lifestyle Insurance.

        Di sini kamu akan diperkenalkan dengan Asuransi Proteksi Income :D

        Jika kamu memilih menu \"Simulasi\".

        Apabila kamu memilih menu \"Pembelian\". 
        
        Kamu akan langsung diarahkan untuk mencari agent yang terdekat di kotamu :D";
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