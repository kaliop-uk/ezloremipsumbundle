<?php

namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

class WordList extends AbstractWordList
{
    protected function getFileName($context = '')
    {
        switch ($context) {
            case 'animals':
            case 'adjectives':
            case 'nouns':
                return __DIR__ . '/../../Resources/wordslists/' . $context . '.txt';
        }
    }

    public function animal()
    {
        return $this->getLine('animals');
    }

    public function adjective()
    {
        return $this->getLine('adjectives');
    }

    public function noun()
    {
        return $this->getLine('nouns');
    }
}
