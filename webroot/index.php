<?php
/**
 * This is a Anax frontcontroller.
 */
require __DIR__ . '/config_with_app.php';

if($di->session->get('flash')) {
    $app->views->add('default/flash', array('flash' => $di->informer->getMessage()), 'flash');
}

$app->router->add('', function() use ($app) {
	$app->theme->setTitle("Home");
	$content = $app->fileContent->get('home.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	$app->views->add('csgo/page', array(
		'content' => $content
	));
	$questions = $app->dispatcher->forward(array(
		'controller' => 'questions',
		'action' => 'latest'
	));
	$users     = $app->dispatcher->forward(array(
		'controller' => 'users',
		'action' => 'mostactive'
	));
	$tags      = $app->dispatcher->forward(array(
		'controller' => 'tags',
		'action' => 'mostused'
	));
	$app->views->add('default/main', array(
		'questions' => $questions,
		'users' => $users,
		'tags' => $tags
	));
});
$app->router->add('about', function() use ($app) {
	$app->theme->setTitle("About");
	$content = $app->fileContent->get('about.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	$app->views->add('csgo/page', array(
		'content' => $content
	));
});
$app->router->add('signin', function() use ($app) {
	$app->dispatcher->forward(array(
		'controller' => 'users',
		'action' => 'login'
	));
});
$app->router->add('signup', function() use ($app) {
	$app->dispatcher->forward(array(
		'controller' => 'users',
		'action' => 'register'
	));
});
$app->router->add('logout', function() use ($app) {
	$app->dispatcher->forward(array(
		'controller' => 'users',
		'action' => 'logout'
	));
});
$app->router->add('ask', function() use ($app) {
	$app->dispatcher->forward(array(
		'controller' => 'questions',
		'action' => 'ask'
	));
});
// Check for matching routes and dispatch to controller/handler of route.
$app->router->handle();
// Render the page.
$app->theme->render();
?>