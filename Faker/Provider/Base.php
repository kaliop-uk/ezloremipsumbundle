<?php


namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

use Faker\Generator;
use Kaliop\eZLoremIpsumBundle\Faker\MaxDistinctGenerator;

abstract class Base
{
    /** @var Generator $generator */
    protected $generator;

    /** @var MaxDistinctGenerator $distinct  */
    protected $distinct;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function maxDistinct($maxElements = 100, $reset = false)
    {
        if ($reset || !$this->distinct) {
            $this->distinct = new MaxDistinctGenerator($this->generator, $maxElements);
        }

        return $this->distinct;
    }
}
