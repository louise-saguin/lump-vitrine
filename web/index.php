<?php 

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

// Require dependendies
$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('Site\\', __DIR__.'/../src/');

// Init Silex
$app = new Silex\Application();
$app['debug'] = true;

// Services

/*TWIG*/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

/*FORM ET MAIL*/
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(

    'swiftmailer.options' => array(
        'host'       => 'smtp.gmail.com',
        'port'       => 465,
        'username'   => 'louise.saguin@hetic.net',
        'password'   => 'mmrmoileneu',
        'encryption' => 'ssl',
        'auth_mode'  => 'login'
    )
));


// Create routes
$app->get('/', function() use ($app){

    return $app['twig']->render('pages/home.twig');
})
    ->bind('home');

$app->match('/contact',function(Request $request) use ($app)
{
    $data = array();

    $form_builder = $app['form.factory']->createBuilder();

    $form_builder->setMethod('post');
    $form_builder->setAction($app['url_generator']->generate('contact'));

    $form_builder->add(
        'email',
        EmailType::class,
        array(
            'label' =>'Mail',
            'trim' =>true,
            'required' =>true
        )
    );

    $form_builder->add(
        'title',
        TextType::class,
        array(
            'label' =>'Objet',
            'trim' =>true,
            'required' =>true
        )
    );

    $form_builder->add(
        'body',
        TextareaType::class,
        array(
            'label' =>'Message',
            'trim' =>true,
            'required' =>true
        )
    );

    $form_builder->add(
        'submit', 
        SubmitType::class,
        array('label' =>'Envoyer')
    );

    $form = $form_builder->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $formData = $form->getData();

        $message = new \Swift_Message();
        $message->setSubject($formData['title']);
        $message->setFrom($formData['email']);
        $message->setTo(array('louisesaguin7@gmail.com'));
        $message->setBody($formData['body']);

        $app['mailer']->send($message);

        return $app->redirect($app['url_generator']->generate('contact'));
    }

    $data['contact_form'] = $form->createView();

    return $app['twig']->render('pages/contact.twig', $data);
})
->bind('contact');

$app->get('/mentions-legales', function() use ($app)
{
	return $app['twig']->render('pages/legal.twig');
})
->bind('legal');

$app->before(function (Request $request) use ($app) {
    $app['twig']->addGlobal('current_page', $request->getRequestUri());
});

$app->error(function() use ($app){

    if($app['debug']){
        return;
    }
    $data = array();
    $data['title'] = 'Error';
    return $app['twig']->render('pages/error.twig', $data);
});

// Run Silex
$app->run();