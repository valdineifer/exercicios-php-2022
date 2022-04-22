<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

/**
 * The package Box\Spout may generate some PHP Deprecated warnings,
 *  as related in: https://github.com/box/spout/issues/846
 * and fixed (but not released) in: https://github.com/box/spout/pull/874
 */

use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use DOMDocument;
use DOMNodeList;
use DOMXPath;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper
{
  const tempDataFilePath = __DIR__ . '/../../webscrapping/temp-data.xlsx';
  const dataFilePath = __DIR__ . '/../../webscrapping/data.xlsx';
  /**
   * Loads paper information from the HTML and creates a XLSX file.
   * 
   * @param DOMDocument $dom The DOMDocument with HTML loaded
   */
  public function scrap(DOMDocument $dom): void
  {
    $authorMaxCount = 0;
    $nodeList = $this->getPapersNodeList($dom);

    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile(self::dataFilePath);
    $rows = [];

    foreach ($nodeList as $node) {
      $paper = new PaperNode($node);

      $authorMaxCount = count($paper->authors) > $authorMaxCount ? count($paper->authors) : $authorMaxCount;

      $rows[] = $this->buildRow($paper);
    }

    $writer->addRow($this->addHeader($authorMaxCount));
    $writer->addRows($rows);

    $writer->close();
  }

  /**
   * Return a list of paper nodes
   * 
   * @param DOMDocument $dom The DOMDocument with HTML loaded
   */
  protected function getPapersNodeList(DOMDocument $dom): DOMNodeList
  {
    $path = new DOMXPath($dom);
    $nodeList = $path->query('//*[starts-with(@class, "paper-card")]');

    return $nodeList ? $nodeList : new DOMNodeList();
  }

  /**
   * Build the header row
   * 
   * @return Row A Row containing the header cells
   */
  protected function addHeader($authorsCount): Row
  {
    $headerArray = ['ID', 'Title', 'Type'];

    for ($i = 0; $i < $authorsCount; $i++) {
      $str = 'Author ' . ($i + 1);
      array_push($headerArray, $str, $str . ' Institution');
    }

    $style = (new StyleBuilder())
      ->setFontBold()
      ->build();

    return WriterEntityFactory::createRowFromArray($headerArray, $style);
  }

  /**
   * Build row for one paper
   * 
   * @return Row A Row containing the paper informations
   */
  protected function buildRow(PaperNode $paper): Row
  {
    $data = [$paper->id, $paper->title, $paper->type];

    $array_flatten = function (array $array) {
      $return = array();
      array_walk_recursive($array, function ($a) use (&$return) {
        $return[] = $a;
      });
      return $return;
    };

    return WriterEntityFactory::createRowFromArray(
      array_merge(
        $data,
        $array_flatten($paper->authors)
      )
    );
  }
}
