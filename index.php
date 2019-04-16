<?php

/**
 * require vendor
 */
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

/**
 * Handler Post Comment
 */
$handler = new PostCommentHandler();

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

    $handler->handleSalaryInput($handler->getQiscusCommentMessage()['text']);

    switch ($handler->getQiscusCommentMessage()['text']) {

        case 'start':
            $handler->answerGetStarted();
            break;

        case 'simulasi':
            $handler->answerSimulation();
            break;

        case 'pembelian':
            
            // $handler->answerBuy();

            exit;

            break;

        case 'daftar':
        //     $handler->answerRegisterPolice();
            exit;
            break;

        case 'pilih_agen':

            $handler->answerAgentChoosen();

            break;

        case 'lengkapi':

        //     $handler->answerCompleteForm();
            exit;
            break;

        case 'submit_form':
            
            $handler->answerFormSubmit();

            break;
        
        default:
            $handler->makeAnswerNotFound();    
            break;
    }

} else {

    $handler->makeAnswerNotFound();
    
}