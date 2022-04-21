<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country, that is also a player.
 */
class BaseCountry implements CountryInterface
{

  /**
   * The name of the country.
   *
   * @var string
   */
  protected $name;

  /**
   * Array of neigbors
   *
   * @var string[]
   */
  protected $neighbors;

  /**
   * The number of troops
   * 
   * @var int
   */
  protected $numberOfTroops;

  /**
   * Indicates if the country was conquered or not
   * 
   * @var bool
   */
  protected $conquered;

  /**
   * Builder.
   *
   * @param string $name
   *   The name of the country.
   */
  public function __construct(string $name)
  {
    $this->name = $name;
    $this->numberOfTroops = 3;
    $this->conquered = false;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setNeighbors(array $neighbors): void
  {
    $this->neighbors = $neighbors;
  }

  public function getNeighbors(): array
  {
    return $this->neighbors;
  }

  public function getNumberOfTroops(): int
  {
    return $this->numberOfTroops;
  }

  public function increaseNumberOfTroops(int $quantity = 1): void
  {
    $this->numberOfTroops += $quantity;
  }

  public function isConquered(): bool
  {
    return $this->conquered;
  }

  public function setAsConquered(): void
  {
    $this->conquered = true;
  }

  public function conquer(CountryInterface $conqueredCountry): void
  {
    $this->neighbors += $conqueredCountry->getNeighbors();
  }

  public function killTroops(int $killedTroops): void
  {
    $this->numberOfTroops -= $killedTroops;
  }
}
