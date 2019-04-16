<?php 

use GuzzleHttp\Client;

class PostCommentHandler {    
    
    private $qiscusComment;
    private $client;

    public function __construnct() {
    }

    private function createHeaders() {
        $this->headers = [
            'QISCUS_APP_ID' => getenv('QISCUS_APP_ID'),
            'QISCUS_SDK_SECRET' => getenv('QISCUS_SDK_SECRET'),
            'Content-Type' => 'application/json'
        ];
    }

    private function createClient() {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => getenv('BASE_URL')
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
            'sender_email' => getenv('SENDER'),
            'room_id' => getenv('ROOM_ID'),
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
        ]);
    }

    public function makeTest() {
        return $this->request([
            'sender_email' => getenv('SENDER'),
            'room_id' => getenv('ROOM_ID'),
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

        $this->makeMessage('Selamat datang di produk Proteksi Penghasilan.

        Proteksi Penghasilan akan melindungi penghasilan Anda apabila terjadi risiko meninggal dunia.
        
        Dengan memberikan penghasilan pengganti kepada keluarga yang dicintai selama 5 tahun.');

        $message = "Anda ingin tahu bagaimana kami melindungi penghasilan Anda ? silakan pilih menu `simulasi`.

        Apabila Anda sudah paham dengan produk ini, Anda dapat langsung melakukan pembelian dengan memilih menu `pembelian`
        ";

        return $this->request([
            'sender_email' => getenv('TARGET_EMAIL'),
            'room_id' => getenv('ROOM_ID'),
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
        $message = 'Yuk, tulis penghasilan Anda setiap bulannya !';

        return $this->makeMessage($message);
    }

    public function answerBuy() {
        // todo
        $message = 'halo ini buy';
        
        return $this->makeMessage($message);
    }

    public function answerAgentChoosen() {
        // todo
        $payload = $this->getQiscusCommentMessage()['payload']['content'];

        $message = 'Anda telah memilih agen kami bernama '.$payload['name'];
        
        // return $this->makeMessage($message);

        return $this->request([
            'sender_email' => getenv('TARGET_EMAIL'),
            'room_id' => getenv('ROOM_ID'),
            'type' => 'card',
            'payload' => [
                'text' => $message,
                'image' => $payload['image_url'],
                'title' => $message,
                'description' => 'Silakan Anda lengkapi form untuk melakukan pembelian produk Proteksi Penghasilan',
                'url' => 'http://anu.com',
                'buttons' => [
                    [
                        'label' => 'lengkapi',
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

    public function answerFormSubmit() {
        return $this->makeMessage('Terimakasih telah menjadi bagian dari kami.');
    }

    public function answerCompleteForm() {
        return $this->makeMessage('halo');
    }

    public function makeMessage($message = 'Hello') {
        $this->request([
            'sender_email' => getenv('TARGET_EMAIL'),
            'room_id' => getenv('ROOM_ID'),
            'message' => $message
        ]);
    }

    public function handleSalaryInput($salary = 0) {
        if (is_numeric($salary)) {

            $result = 0.05 * $salary;

            $message = "Hanya dengan menyisihkan ".$result." setiap bulan,
            maka penghasilan Anda akan dilindungi dimana apablia risiko meninggal dunia. ";
            
            $this->makeMessage($message);

            $result = $salary * 60;

            $message = "Keluarga yang Anda cintai akan mendapat penghasilan pengganti sebesar :
            ".$result." (".$salary." x 60bln).";
            
            $this->makeMessage($message);

            $message = "Selain itu, apabila uang yang Anda sisihkan juga bisa kembali dalam bentuk investasi jika tidak terjadi sesuatu hal yang berrisiko.";

            $this->makeMessage($message);

            $message = "Tunggu apalagi ? Daftar sekarang juga dengan memilih menu daftar di bawah !";
            
            $this->request([
                'sender_email' => getenv('TARGET_EMAIL'),
                'room_id' => getenv('ROOM_ID'),
                'type' => 'buttons',
                'payload' => [
                    'text' => $message,
                    'buttons' => [
                        [
                            'label' => 'daftar',
                            'type' => 'postback',
                            'payload' => [
                                'url' => 'http://api.anu.com',
                                'method' => 'get',
                                'payload' => null
                            ],
                        ],
                    ]
            
                ]
            ]);           

            exit;
        }
    }

    public function answerRegisterPolice() {
        
        $message = "daftar_asuransi";

        $this->makeMessage($message);
    }

}