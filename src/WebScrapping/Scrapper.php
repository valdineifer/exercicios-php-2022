<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use Box\Spout\Common\Entity\Row;
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

  /**
   * Loads paper information from the HTML and creates a XLSX file.
   * 
   * @param DOMDocument $dom The DOMDocument with HTML loaded
   */
  public function scrap(DOMDocument $dom): void
  {
    $nodeList = $this->getPapersNodeList($dom);

    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile('data.xlsx');
    $writer->addRow($this->addHeader());

    foreach ($nodeList as $node) {
      $paper = new PaperNode($node);

      $writer->addRow($this->buildRow($paper));
    }

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
   * Add a header to the XLSX file
   * 
   * @return Row A Row containing the header cells
   */
  protected function addHeader(): Row
  {
    $style = (new StyleBuilder())
      ->setFontBold()
      ->build();

    return WriterEntityFactory::createRowFromArray([
      'ID', 'Title', 'Type',
      'Author 1', 'Author 1 Institution',
      'Author 2', 'Author 2 Institution',
      'Author 3', 'Author 3 Institution',
      'Author 4', 'Author 4 Institution',
      'Author 5', 'Author 5 Institution',
      'Author 6', 'Author 6 Institution',
      'Author 7', 'Author 7 Institution',
      'Author 8', 'Author 8 Institution',
      'Author 9', 'Author 9 Institution'
    ], $style);
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
