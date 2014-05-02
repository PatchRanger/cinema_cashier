<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new \CinemaCashier\App();

// @todo Comment when deploying to production.
// Enable error reporting for debug purposes.
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$app['debug'] = true;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;

// Register Doctrine DBAL.
// @see http://silex.sensiolabs.org/doc/providers/doctrine.html
$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
    /* @todo Switch to real database.
    'driver' => 'pdo_mysql',
    'dbname' => 'cinema_cashier',
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => 'root',
    */
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/sqlite.db',
    //'memory' => TRUE,
    'charset' => 'utf8'
  )
));

$app->register(new \Nutwerk\Provider\DoctrineORMServiceProvider(), array(
  // @todo Is it correct?
  'db.orm.proxies_dir' => __DIR__.'/../cache/doctrine/proxy',
  'db.orm.proxies_namespace' => 'DoctrineProxy',
  'db.orm.cache' =>
    !$app['debug'] && extension_loaded('apc') ? new ApcCache() : new ArrayCache(),
  'db.orm.auto_generate_proxies' => TRUE,
  'db.orm.entities' => array(array(
    'type' => 'annotation',                 // Entity definition.
    'path' => 'src/CinemaCashier/Entity',       // Path to our entity classes.
    'namespace' => 'CinemaCashier\Entity',  // Namespace for our classes.
  )),
));

// Home page.
$app->get('/', function() use ($app) {
  return
    "<h1>Cinema Cashier</h1>"."<p>Hello, here is the list of API functions:
    <ul>
    <li><a href='/api/cinema/1/schedule?hall=1'>Check cinema scedule</a></li>
    </ul>
    </p>";
});

// Cinema.
$app->get('/api/cinema/{cinema_id}/schedule', function ($cinema_id) use ($app) {
  // First try to get cinema by provided id.
  try {
    $sql = "SELECT * FROM cinema WHERE id=?";
    $cinema = $app['db']->fetchAssoc($sql, array((int) $cinema_id));
  } catch (Exception $e) {
    $app->abort(500, $e->getMessage());
  }
  if (!empty($cinema)) {
    $output = "<h1>{$cinema['title']}</h1>"."<p>{$cinema['description']}</p>";
  }
  else {
    $app->abort(404, "No cinema with id {$cinema_id}");
  }
  if ($hall_id = $app['request']->get('hall')) {
    // Find corresponding hall for cinema.
    // @todo Validation input parameter.
    // @todo DRY
    try {
      $sql = "SELECT * FROM hall WHERE cinema=? AND id=?";
      $hall = $app['db']->fetchAssoc($sql, array((int) $cinema_id, (int) $hall_id));
    } catch (Exception $e) {
      $app->abort(500, $e->getMessage());
    }
    if (!empty($hall)) {
      $output .= "<p>Here is the schedule for hall #{$hall_id} of \"{$cinema['title']}\".</p>";
    }
    else {
      $app->abort(404, "No hall #{$hall_id} in \"{$cinema['title']}\"");
    }
  }
  return $output;
});
return $app;
