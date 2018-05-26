<?php

namespace Kaliop\eZLoremIpsumBundle\Faker;

use Faker\Generator;

/**
 * Proxy for other generators, to return only a limited amount of distinct values. Works with
 * Faker\Generator\Base->unique()
 */
class MaxDistinctGenerator
{
    protected $generator;
    protected $maxElements;
    protected $maxTries = 10000;
    protected $uniques = array();

    /**
     * @param Generator $generator
     * @param integer $maxElements
     */
    public function __construct(Generator $generator, $maxElements = 100)
    {
        $this->generator = $generator;
        $this->maxElements = $maxElements;
    }

    /**
     * Catch and proxy all generator calls but return only unique values
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->__call($attribute, array());
    }

    /**
     * Catch and proxy all generator calls with arguments and return only up to maxElements unique values.
     * NB: does *not* guarantee that all returned values are unique - combine it with a `unique()` modifier for that.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!isset($this->uniques[$name])) {
            $this->uniques[$name] = array();
        }

        if (count($this->uniques[$name]) >= $this->maxElements) {
            return unserialize($this->uniques[$name][mt_rand(0, count($this->uniques[$name]) - 1)]);
        }

        $res = call_user_func_array(array($this->generator, $name), $arguments);
        $sres = serialize($res);
        if (!in_array($sres, $this->uniques[$name])) {
            $this->uniques[$name][] = $sres;
        }
        return $res;
    }
}
