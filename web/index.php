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
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../views',
));

// Create routes
$app->get('/', function() use ($app)
{
	return $app['twig']->render('pages/home.twig');
})
->bind('home');

$app->get('/contact', function() use ($app)
{
	return $app['twig']->render('pages/contact.twig');
})
->bind('contact');

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