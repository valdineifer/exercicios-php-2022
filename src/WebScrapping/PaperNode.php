<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use DOMElement;

/**
 * Used to extract and have the necessary data of a paper, to build the sheet.
 */
class PaperNode
{
  private DOMElement $element;
  public readonly string $id;
  public readonly string $title;
  public readonly string $type;
  public readonly array $authors;

  /**
   * Extract necessary data from the paper DOM Element
   * 
   * @param DOMElement $element The element of a paper
   */
  public function __construct(DOMElement $element)
  {
    $this->element = $element;

    $this->title = $this->element->firstChild->nodeValue;

    $this->extractAuthors();
    $this->extractTypeAndId();
  }

  /**
   * Extracts 'type' and 'id' from a 'div' element on the paper section
   */
  public function extractTypeAndId(): void
  {
    $divNode = $this->element->childNodes->item(2);

    $this->type = $divNode->childNodes->item(0)->nodeValue;
    $this->id = $divNode->childNodes->item(1)->nodeValue;
  }

  /**
   * Extracts the paper 'authors' from the paper section
   */
  private function extractAuthors(): void
  {
    $authorsNodeList = $this->element->childNodes->item(1)->childNodes;
    $authorsArray = [];

    foreach ($authorsNodeList as $author) {
      $sanitizedName = trim(str_replace(';', '', $author->nodeValue));

      if (
        $author instanceof DOMElement
        && strlen($sanitizedName)
      ) {
        array_push($authorsArray, [
          'name' => $sanitizedName,
          'institution' => $author->attributes->getNamedItem('title')->nodeValue
        ]);
      }
    }

    $this->authors = $authorsArray;
  }
}
