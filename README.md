# Qiscus Webhook Chat

## Requirements

* PHP 7
* [Composer](https://getcomposer.org/)
* [ngrok](https://ngrok.com/)
* [Qiscus SDK](https://www.qiscus.com)

## How to run

* Clone this repository and install dependencies

```bash
$ git clone https://github.com/andhikayuana/qiscus-webhook-demo
$ cd qiscus-webhook-demo
$ composer install
```

* Create App in Qiscus Dashboard
* Create New 2 User by using [Qiscus Web Sample](https://github.com/andhikayuana/qiscus-web-sample)
* Config your `.env` file

```bash
$ cp .env.example .env
```

* Run webhook server

```bash
$ php -S localhost:3000
```

* Tunneling your webhook server

```bash
$ ngrok http 3000
```

* Register your webhook url by copy your ngrok url at [Qiscus Dashboard](https://dashboard.qiscus.com/dashboard)

* Enjoy!