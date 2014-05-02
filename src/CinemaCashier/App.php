<?php

namespace CinemaCashier;

use Silex\Application;
use CinemaCashier\Entity\Cinema as Cinema;
use CinemaCashier\Entity\Hall as Hall;
use CinemaCashier\Entity\Film as Film;
//use CinemaCashier\Entity\Seat as Seat;

/**
 * The Cinema Cashier application main class.
 *
 * @author Dmitry Danilson <patchranger@gmail.com>
 */
class App extends \Silex\Application
{
  public function defaultContent()
  {
    $em = $this['db.orm.em'];
    // Add cinema.
    $cinema = new Cinema();
    $cinema->setTitle('Test Cinema');
    $cinema->setDescription('Lorem ipsum dolor');
    $em->persist($cinema);
    // Add halls.
    $hall = new Hall();
    $hall->setCinema(1);
    $em->persist($hall);
    $hall = new Hall();
    $hall->setCinema(1);
    $em->persist($hall);
    // @todo Add seats.
    /* @todo Add films.
    $film = new Film();
    $film->setTitle();
    $cinema->setDescription('Description of awesome film.');
    */
    // @todo Add sessions.
    $em->flush();
  }
}