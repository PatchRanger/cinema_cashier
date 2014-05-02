<?php

namespace CinemaCashier\Entity;

/**
 * @Entity
 * @Table(name="hall")
 */
class Hall
{
  /**
   * @Column(type="integer")
   * @Id
   * @GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @Column(type="integer")
   */
  protected $cinema;

  public function getId()
  {
    return $this->id;
  }

  public function getCinema()
  {
    return $this->cinema;
  }

  public function setCinema($cinema)
  {
    $this->cinema = $cinema;
    return $this;
  }

  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }
}