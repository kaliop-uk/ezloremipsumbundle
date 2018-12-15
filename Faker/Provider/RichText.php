<?php

namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

class RichText extends Base
{
    const P_TAG = "para";
    const A_TAG = "link";
    const E_TAG = "ezembed";
    const TABLE_TAG = "informaltable";
    const TR_TAG = "tr";
    const TD_TAG = "td";
    const UL_TAG = "itemizedlist";
    const OL_TAG = "orderedlist";
    const LI_TAG = "listitem";
    const H_TAG = "title";
    const EMPHASIS_TAG = "emphasis";
    const CUSTOM_TAG = "custom";

    /**
     * @param integer $maxDepth
     * @param integer $maxWidth
     *
     * @return string
     */
    public function randomRichText($maxDepth = 4, $maxWidth = 4)
    {
        $document = new \DOMDocument();

        $body = $document->createElementNS("http://docbook.org/ns/docbook", "section");
        $body->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $body->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ezxhtml', 'http://ez.no/xmlns/ezpublish/docbook/xhtml');
        $body->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ezcustom', 'http://ez.no/xmlns/ezpublish/docbook/custom');
        $body->setAttribute('version', '5.0-variant ezpublish-1.0');

        $this->addRandomSubTree($body, $maxDepth, $maxWidth);

        $document->appendChild($body);

        $out = $document->saveXML();

        return $out;
    }

    private function addRandomSubTree(\DOMElement $root, $maxDepth, $maxWidth)
    {
        $maxDepth--;
        if ($maxDepth <= 0) {
            $this->addRandomLeaf($root);
            return $root;
        }

        $siblings = mt_rand(1, $maxWidth);
        for ($i = 0; $i < $siblings; $i++) {
            if ($maxDepth == 1) {
                $this->addRandomLeaf($root);
            } else {
                $this->addRandomSubTree($root, mt_rand(1, $maxDepth), $maxWidth);
            }
        };
        return $root;
    }

    private function addRandomLeaf(\DOMElement $node)
    {
        $rand = mt_rand(1, 10);
        switch($rand) {
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
        $node = $element->ownerDocument->createElement(static::E_TAG);
        $node->setAttribute("view", "embed");
        /// @todo improve: generation of a radmon object id
        $node->setAttribute("xlink:href", 'ezcontent://' . mt_rand(1, 100000));
        $element->appendChild($node);
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
                $p->setAttribute("ezxhtml:textalign", "right");
                break;
            case 2:
                $p->setAttribute("ezxhtml:textalign", "center");
                break;
        }
        $p->appendChild($text);
        $element->appendChild($p);
    }

    private function addRandomA(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::A_TAG);
        $node->setAttribute("xlink:href", 'ezurl://28');
        $node->setAttribute('xlink:show', 'none');
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomH(\DOMElement $element, $maxLength = 10)
    {
        $h = static::H_TAG;
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement($h);
        $node->setAttribute('ezxhtml:level', mt_rand(1, 6));
        $node->appendChild($text);
        $element->appendChild($node);
    }

    private function addRandomB(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::EMPHASIS_TAG);
        $node->setAttribute('role', 'strong');
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomU(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::EMPHASIS_TAG);
        $node->setAttribute('role', 'underlined');
        $node->appendChild($text);
        $this->wrapInParagraph($node, $element);
    }

    private function addRandomI(\DOMElement $element, $maxLength = 10)
    {
        $text = $element->ownerDocument->createTextNode($this->getSentence(mt_rand(1, $maxLength)));
        $node = $element->ownerDocument->createElement(static::EMPHASIS_TAG);
        $node->setAttribute('role', 'emphasis');
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
        $element->appendChild($ul);
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
        $element->appendChild($ul);
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
