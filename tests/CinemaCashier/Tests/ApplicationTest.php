<?php


namespace CinemaCashier\Tests;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CinemaCashier\App;

/**
 * Application test cases.
 *
 * @author Dmitry Danilson <patchranger@gmail.com>
 */
// @todo Replace it with just \PHPUnit_Framework_TestCase as we don't need testing using datasets.
//class ApplicationTest extends \PHPUnit_Framework_TestCase
class ApplicationTest extends \PHPUnit_Extensions_Database_TestCase
{
  // Only instantiate pdo once for test clean-up/fixture load.
  static private $pdo = null;

  // Only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test.
  private $conn = null;

  public function getConnection()
  {
    if ($this->conn === null) {
      if (self::$pdo == null) {
        // @todo Switch to real database.
        //self::$pdo = new PDO('mysql:host=localhost;dbname=cinema_cashier', 'root', 'root');
        //self::$pdo = new PDO('sqlite::memory:');
        self::$pdo = new \PDO('sqlite:' . __DIR__ . '/../../../sqlite.db');
      }
      //$this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
      $this->conn = $this->createDefaultDBConnection(self::$pdo, 'cinema_cashier');
    }
    return $this->conn;
  }

  public function getDataSet()
  {
    // Clone database from file-based to memory-based.
    $ds = new \PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
    $ds->addTable('cinema');
    return $ds;
  }

  public function setUp()
  {
    // Init the application for common usage.
    $this->app = require __DIR__.'/../../../app.php';
    // Fill the database with default content.
    $this->app->defaultContent();
  }

  protected function checkResponse($url, $method = 'get', $statusCode = 200)
  {
    $request = Request::create($url, $method);
    $response = $this->app->handle($request);
    $this->assertEquals($statusCode, $response->getStatusCode(), "{$url}: Status code {$response->getStatusCode()} != {$statusCode}");
    return $response;
  }

  public function testCheckCinema() {
    // Нужно пользователю дать возможность просмотреть расписание кинотеатра, с возможностью фильтрации по залу:
    // GET /api/cinema/<название кинотеатра>/schedule[?hall=номер зала]
    $this->checkResponse('/api/cinema/1/schedule');
    $this->checkResponse('/api/cinema/1/schedule?hall=1');
  }

  public function testCheckFilm() {
    //Также надо дать возможность просмотреть в каких кинотеатрах/залах идёт конкретный фильм:
    //GET /api/film/<название фильма>/schedule
    $this->checkResponse('/api/film/1/schedule');
  }

  public function testCheckSeats() {
    // @todo Replace "places" with "seats".
    //Затем надо проверить, какие места свободны на конкретный сеанс:
    //GET /api/session/<id сеанса>/places
    $this->checkResponse('/api/session/1/places');
  }

  public function testBooking() {
    //И дать возможность купить билет:
    //POST /api/tickets/buy?session=<id сеанса>&places=1,3,5,7
    // Результатом запроса должен быть уникальный код, характеризующий этот набор билетов
    echo $this->checkResponse('/api/tickets/buy?session=1&places=1,3,5,7', 'post');
  }

  public function testReject() {
    //И отменить покупку, но не раньше, чем за час до начала сеанса:
    //POST /api/tickets/reject/<уникальный код>
    $this->checkResponse('/api/tickets/reject/unique_hash', 'post');
  }
}
