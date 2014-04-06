<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->get('/', function (Request $request) use ($app) {
  $keyword = $request->get('q');
  $results = Array();
  $xml = simplexml_load_file('http://xlab.pl/feed/');
  
  if ($xml) {
    $pattern = '/' . htmlspecialchars($keyword, ENT_QUOTES) . '/i';
    
    foreach ($xml->channel->item as $item) {
      $add = true;
      
      if (
        $keyword && !preg_match($pattern, $item->description->__toString())
      ) {
        $add = false;
      }
      
      if ($add) {
        $results[] = Array(
          'title' => $item->title->__toString(),
          'content' => strip_tags($item->children("content", true)->__toString()),
        );
      }
    }
  }
  
  return $app['twig']->render('index.twig', array(
    'results' => $results,
    'keyword' => $keyword,
  ));
});

$app->run();
