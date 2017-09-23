<?php

require 'vendor/autoload.php';

const BASE_URL = 'http://lifestyle-fct27dl8lmo.qiscus.com';
const QISCUS_SDK_SECRET = 'd01f3afa94edd84c44057dbc48ae54ea';

$handler = new PostCommentHandler(BASE_URL, QISCUS_SDK_SECRET);

$handler->run();

if ($handler->getQiscusComment()['type'] == 'post_comment_mobile') {
    
    $handler->makeAnswer();

} else {

    $handler->makeAnswerNotFound();
    
}