<?php

namespace CinemaCashier\Entity;

/**
 * @Entity
 * @Table(name="cinema")
 */
class Cinema
{
  /**
   * @Column(type="integer")
   * @Id
   * @GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @Column(type="string", length=100)
   */
  protected $title;

  /**
   * @Column(type="text")
   */
  protected $description;

  public function getId()
  {
    return $this->id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription($description)
  {
    $this->description = $description;
    return $this;
  }
}