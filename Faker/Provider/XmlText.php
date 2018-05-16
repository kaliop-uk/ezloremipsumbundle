<?php

namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

/**
 * NB: A very crude implementation for now
 * @see https://github.com/ezsystems/ezpublish-kernel/tree/master/eZ/Publish/Core/FieldType/Tests/RichText/Converter/Xslt/_fixtures/ezxml
 */
class XmlText extends Base
{
    const P_TAG = "paragraph";
    const A_TAG = "link";
    const E_TAG = "embed";
    const TABLE_TAG = "table";
    const TR_TAG = "tr";
    const TD_TAG = "td";
    const UL_TAG = "ul";
    const OL_TAG = "ol";
    const LI_TAG = "li";
    const H_TAG = "header";
    const B_TAG = "strong";
    const I_TAG = "emphasize";
    const CUSTOM_TAG = "custom";

    /**
     * @param integer $maxDepth
     * @param integer $maxWidth
     *
     * @return string
     */
    public function randomXmlText($maxDepth = 4, $maxWidth = 4)
    {
        $document = new \DOMDocument();

        $body = $document->createElement("section");
        $body->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', 'http://ez.no/namespaces/ezpublish3/xhtml/');
        $body->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:image', 'http://ez.no/namespaces/ezpublish3/image/');
        $body->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:custom', 'http://ez.no/namespaces/ezpublish3/custom/');
        $this->addRandomSubTree($body, $maxDepth, $maxWidth);

        $document->appendChild($body);

        $out = $document->saveXML();

        return $out;
    }

    private function addRandomSubTree(\DOMElement $root, $maxDepth, $maxWidth)
    {
        $maxDepth--;
        if ($maxDepth <= 0) {
            return $root;
        }

        $siblings = mt_rand(1, $maxWidth);
        for ($i = 0; $i < $siblings; $i++) {
            if ($maxDepth == 1) {
                $this->addRandomLeaf($root);
            } else {
                $sibling = $root->ownerDocument->createElement("section");
                $root->appendChild($sibling);
                $this->addRandomSubTree($sibling, mt_rand(0, $maxDepth), $maxWidth);
            }
        };
        return $root;
    }

    private function addRandomLeaf(\DOMElement $node)
    {
        $rand = mt_rand(1, 10);
        switch($rand){
            case 1:
                $this->addRandomE($node);
                break;
            case 2:
                $this->addRandomA($node);
                break;
            case 3:
                $this->addRandomOL($node);
                break;
            case 4:
                $this->addRandomUL($node);
                break;
            case 5:
                $this->addRandomH($node);
                break;
            case 6:
                $this->addRandomB($node);
                break;
            case 7:
                $this->addRandomI($node);
                break;
            case 8:
                $this->addRandomTable($node);
                break;
            case 9:
                $this->addRandomU($node);
                break;
            default:
                $this->addRandomP($node);
                break;
        }
    }

    private function addRandomE(\DOMElement $element)
    {
        $p = $element->ownerDocument->createElement(static::P_TAG);
        $node = $element->ownerDocument->createElement(static::E_TAG);
        $node->setAttribute("view", "embed");
        $node->setAttribute("size", "medium");
        /// @todo improve: generation of a radmon object id
        $node->setAttribute("object_id", mt_rand(1, 100000));
        $p->appendChild($node);
        $element->appendChild($p);
    }

    /**
     * @todo add random align
     * @param \DOMElement $element
     * @param int $maxLength
     */
    private function addRandomP(\DOMElement $element, $maxLength = 50)
    {
        $p = $element->ownerDocument->createElement(static::P_TAG);
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        // left-aligned paragraphs have double frequency
        switch (mt_rand(1, 4)) {
            case 1:
                $p->setAttribute("align", "right");
                break;
            case 2:
                $p->setAttribute("align", "center");
                break;
        }
        $p->appendChild($text);
        $element->appendChild($p);
    }

    private function addRandomA(\DOMElement $element, $maxLength = 10)
    {
        $p = $element->ownerDocument->createElement(static::P_TAG);
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::A_TAG);
        $node->setAttribute("url", $this->getUrl());
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomH(\DOMElement $element, $maxLength = 10)
    {
        $h = static::H_TAG;
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement($h);
        $node->appendChild($text);
        $element->appendChild($node);

    }

    private function addRandomB(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::B_TAG);
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomU(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::CUSTOM_TAG);
        $node->setAttribute('name', 'underline');
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomI(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::I_TAG);
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomTable(\DOMElement $element, $maxRows = 10, $maxCols = 6, $maxLength = 10)
    {
        $rows = mt_rand(1, $maxRows);
        $cols = mt_rand(1, $maxCols);

        $table = $element->ownerDocument->createElement(static::TABLE_TAG);

        for ($i = 0; $i < $rows; $i++) {
            $tr = $element->ownerDocument->createElement(static::TR_TAG);
            $table->appendChild($tr);
            for ($j = 0; $j < $cols; $j++) {
                $th = $element->ownerDocument->createElement(static::TD_TAG);
                $th->textContent = $this->getSentence(mt_rand(1, $maxLength));
                $tr->appendChild($th);
            }
        }
        $this->wrapInParagraph($table, $element);
    }

    private function addRandomUL(\DOMElement $element, $maxItems = 11, $maxLength = 4)
    {
        $num = mt_rand(1, $maxItems);
        $ul = $element->ownerDocument->createElement(static::UL_TAG);
        for ($i = 0; $i < $num; $i++) {
            $li = $element->ownerDocument->createElement(static::LI_TAG);
            $lip = $element->ownerDocument->createElement(static::P_TAG);
            $lip->textContent = $this->getSentence(mt_rand(1, $maxLength));
            $li->appendChild($lip);
            $ul->appendChild($li);
        }
        $this->wrapInParagraph($ul, $element);
    }

    private function addRandomOL(\DOMElement $element, $maxItems = 11, $maxLength = 4)
    {
        $num = mt_rand(1, $maxItems);
        $ul = $element->ownerDocument->createElement(static::OL_TAG);
        for ($i = 0; $i < $num; $i++) {
            $li = $element->ownerDocument->createElement(static::LI_TAG);
            $lip = $element->ownerDocument->createElement(static::P_TAG);
            $lip->textContent = $this->getSentence(mt_rand(1, $maxLength));
            $li->appendChild($lip);
            $ul->appendChild($li);
        }
        $this->wrapInParagraph($ul, $element);
    }

    protected function wrapInParagraph(\DOMElement $element, \DOMElement $parent, $maxLength = 10)
    {
        $p = $element->ownerDocument->createElement(static::P_TAG);
        $chance = mt_rand(1, 4);
        if ($chance == 1 || $chance == 4) {
            $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)) . ' ');
            $p->appendChild($text);
        }
        $p->appendChild($element);
        if ($chance == 2 || $chance == 4) {
            $text = $element->ownerDocument->createTextNode(' ' . $this->getSentence(mt_rand(1, $maxLength)));
            $p->appendChild($text);
        }
        $parent->appendChild($p);
    }
    
    protected function getSentence($nbWords = 6, $variableNbWords = true)
    {
        return $this->generator->sentence($nbWords, $variableNbWords);
    }

    protected function getUrl()
    {
        return $this->generator->url;
    }
}
