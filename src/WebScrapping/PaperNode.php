<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use DOMElement;

class PaperNode
{
  private DOMElement $element;
  public readonly string $id;
  public readonly string $title;
  public readonly string $type;
  public readonly array $authors;

  public function __construct(DOMElement $element)
  {
    $this->element = $element;

    $this->title = $this->element->firstChild->nodeValue;

    $this->extractAuthors();
    $this->extractTypeAndId();
  }

  public function extractTypeAndId(): void
  {
    $divNode = $this->element->childNodes->item(2);

    $this->type = $divNode->childNodes->item(0)->nodeValue;
    $this->id = $divNode->childNodes->item(1)->nodeValue;
  }

  private function extractAuthors(): void
  {
    $authorsNodeList = $this->element->childNodes->item(1)->childNodes;
    $authorsArray = [];

    foreach ($authorsNodeList as $author) {
      if ($author instanceof DOMElement) {
        array_push($authorsArray, [
          'name' => trim(str_replace(';', '', $author->nodeValue)),
          'institution' => $author->attributes->getNamedItem('title')->nodeValue
        ]);
      }
    }

    $this->authors = $authorsArray;
  }
}
