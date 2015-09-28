<?php

require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set("Europe/London");


// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;

// $log = new Logger('name');
// $log->pushHandler(new StreamHandler('app.log', Logger::WARNING));
// $log->addWarning('Foo');

$app = new \Slim\Slim( array(
    'view' => new \Slim\Views\Twig()
));

$view = $app->view();

### TO USE TWIG OPTIONS :
$view->parserOptions = array(
    'debug' => true
);

### HELPER FUNCTIONS TO USE TWIG EXTENSIONS:
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);



$app->get('/', function() use ($app){
    $app->render('about.twig');
})->name('home');

$app->get('/contact', function() use ($app){
    $app->render('contact.twig');
    
})->name('contact');

$app->post('/contact', function() use ($app){

    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $msg = $app->request->post('msg');
    
    if (!empty($name) && !empty($email) &&!empty($msg)) {
        $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
        $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $cleanMsg = filter_var($msg, FILTER_SANITIZE_STRING);

    } else {
        //message the user -> problem 
        $app->redirect('/contact');
    }
    
    $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail');
    $mailer = \Swift_Mailer::newInstance($transport);
    
});

$app->run();
