<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay;

use Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface;

/**
 * A manager that will roll the dice and compute the winners of a battle.
 */
class Battlefield implements BattlefieldInterface
{

  public function rollDice(CountryInterface $country, bool $isAttacking): array
  {
    $values = [];
    $numTroops = $country->getNumberOfTroops();

    if ($isAttacking)
      $numTroops--;

    for ($i = 0; $i < $numTroops; $i++)
      array_push($values, rand(1, 6));

    return $values;
  }

  public function computeBattle(
    CountryInterface $attackingCountry,
    array $attackingDice,
    CountryInterface $defendingCountry,
    array $defendingDice
  ): void {
    rsort($attackingDice);
    rsort($defendingDice);

    $attackingPoints = 0;
    $defendingPoints = 0;

    while (count($attackingDice) > 0 && count($defendingDice) > 0) {
      if ($attackingDice[0] > $defendingDice[0]) {
        $attackingPoints++;
      } else {
        $defendingPoints++;
      }

      unset($attackingDice[0]);
      unset($defendingDice[0]);
    }

    $attackingCountry->killTroops($defendingPoints);
    $defendingCountry->killTroops($attackingPoints);

    if (count($attackingDice) > count($defendingDice)) {
      $attackingCountry->conquer($defendingCountry);
    } else if (count($attackingDice) < count($defendingDice)) {
      $defendingCountry->conquer($attackingCountry);
    } else if ($attackingPoints > $defendingPoints) {
      $attackingCountry->conquer($defendingCountry);
    } else if ($attackingPoints < $defendingPoints) {
      $defendingCountry->conquer($attackingCountry);
    }
  }
}
