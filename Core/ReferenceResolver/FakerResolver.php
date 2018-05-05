<?php

namespace Kaliop\eZLoremIpsumBundle\Core\ReferenceResolver;

use Faker\Factory;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Kaliop\eZMigrationBundle\Core\ReferenceResolver\AbstractResolver;
use Kaliop\eZMigrationBundle\API\EnumerableReferenceResolverInterface;

class FakerResolver extends AbstractResolver implements EnumerableReferenceResolverInterface
{
    protected $referencePrefixes = array('faker:');

    /** @var \Faker\Generator $faker */
    protected $faker;

    public function __construct(ConfigResolverInterface $configResolver, $locale = null)
    {
        parent::__construct();

        if ($locale === null) {
            $locales = $configResolver->getParameter('languages');
            $locale = reset($locales);
        }
        $locale = $this->convertLocale($locale);
        /// @todo allow to register custom providers
        $this->faker = Factory::create($locale);
    }

    /**
     * @param string $identifier format: 'faker:name', 'faker:randomnumber(3)'
     * @return mixed
     * @throws \Exception When trying to retrieve an unset reference
     */
    public function getReferenceValue($identifier)
    {
        $identifier = $this->getReferenceIdentifier($identifier);

        /// @todo this way of matching args does not allow for arguments which contain a comma or right parethesis chars...
        if (preg_match('/^([^(]+)\(([^(]+)\)$/', $identifier, $matches)) {
            $formatter = $matches[1];
            $arguments = array_map('trim', explode(',', $matches[2]));
        } else {
            $formatter = $identifier;
            $arguments = array();
        }

        return $this->faker->format($formatter, $arguments);
    }

    /**
     * converts eng-GB in en_GB
     * @param $locale string
     * @return string
     */
    protected function convertLocale($locale)
    {
        return substr($locale, 0, 2) . '_' . substr($locale, 4);
    }

    /**
     * We implement this method (interface) purely for convenience, as it allows this resolver to be added to the
     * 'custom-reference-resolver' chain and not break migration suspend/resume
     * @return array
     */
    public function listReferences()
    {
        return array();
    }
}