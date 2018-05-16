<?php


namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

use Faker\Generator;

abstract class Base
{
    /** @var Generator $generator */
    protected $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }
}
