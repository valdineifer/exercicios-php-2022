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

        for ($i = 0; $i < 6; $i++)
            array_push($values, rand(1, 6));

        return $values;
    }
}
