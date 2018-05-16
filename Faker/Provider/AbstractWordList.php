<?php

namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

abstract class AbstractWordList extends Base
{
    protected $data = array();

    /**
     * @param string $context
     * @return string
     */
    abstract protected function getFileName($context = '');

    protected function loadData($context = '')
    {
        if (!isset($this->data[$context])) {
            $this->data[$context] = file($this->getFileName($context), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
    }

    protected function getLine($context = '')
    {
        $this->loadData($context);
        $lineNum = mt_rand(0, count($this->data[$context]) - 1);
        return $this->data[$context][$lineNum];
    }
}
