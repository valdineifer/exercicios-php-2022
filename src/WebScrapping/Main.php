<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use DOMDocument;

/**
 * Runner for the Webscrapping exercice.
 */
class Main
{

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void
  {
    $dom = new DOMDocument('1.0', 'utf-8');

    libxml_use_internal_errors(true);
    $dom->loadHTMLFile(__DIR__ . '/../../webscrapping/origin.html', LIBXML_NOBLANKS);

    (new Scrapper())->scrap($dom);

    print "\n===== Planilha 'data.xlsx' criada com sucesso na pasta '/webscrapping' =====\n";
  }
}
