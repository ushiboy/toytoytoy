<?php
namespace ToyToyToy\Tests\Helper;

use \DOMDocument;
use \DOMXPath;

class HtmlAccessor
{

    private $document;
    private $xpath;

    public function __construct(string $html)
    {
        $this->document = new DOMDocument();
        $this->document->loadHTML($html);
        $this->xpath = new DOMXPath($this->document);
    }

    public function getDOMNode(string $path)
    {
        return $this->xpath->query($path)->item(0);
    }

    public function find(string $path)
    {
        return $this->createElement($this->getDOMNode($path));
    }


    protected function createElement($domElement)
    {
        return new class($domElement) {

            private $el;

            public function __construct($el) {
                $this->el = $el;
            }

            public function attr(string $attributeName)
            {
                return $this->el->getAttribute($attributeName);
            }

            public function val()
            {
                return $this->el->getAttribute('value');
            }
        };
    }

}
