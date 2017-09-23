<?php

/**
 * require vendor
 */
require 'vendor/autoload.php';

/**
 * config
 */
const BASE_URL = 'http://lifestyle-fct27dl8lmo.qiscus.com';
const QISCUS_SDK_SECRET = 'd01f3afa94edd84c44057dbc48ae54ea';
const TARGET_EMAIL = 'ismail@domain.com';
const ROOM_ID = '37537';


/**
 * Handler Post Comment
 */
$handler = new PostCommentHandler(BASE_URL, QISCUS_SDK_SECRET);

if (!empty($_GET['init_start']) && $_GET['init_start'] == '1') {
    /**
     * first state for open chat
     */
     $handler->answerGetStarted();
}

/**
 * Run the handler
 */
$handler->run();

if ($handler->getQiscusComment()['type'] == 'post_comment_mobile') {

    switch ($handler->getQiscusCommentMessage()['text']) {

        case 'start':
            $handler->answerGetStarted();
            break;

        case 'simulasi':
            $handler->answerSimulation();
            break;

        case 'pembelian':
            $handler->answerListAgent();
            break;
        
        default:
            $handler->makeAnswerNotFound();    
            break;
    }

} else {

    $handler->makeAnswerNotFound();
    
}