<?php

namespace Kaliop\eZLoremIpsumBundle\Core\ReferenceResolver;

use Faker\Factory;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Kaliop\eZMigrationBundle\Core\ReferenceResolver\AbstractResolver;
use Kaliop\eZMigrationBundle\API\EnumerableReferenceResolverInterface;
use Kaliop\eZMigrationBundle\API\ReferenceResolverInterface;

class FakerResolver extends AbstractResolver implements EnumerableReferenceResolverInterface
{
    protected $referencePrefixes = array('faker:');

    /** @var \Faker\Generator $faker */
    protected $faker;

    protected $referenceResolver;

    public function __construct(ConfigResolverInterface $configResolver, ReferenceResolverInterface $referenceResolver, $locale = null)
    {
        parent::__construct();

        if ($locale === null) {
            $locales = $configResolver->getParameter('languages');
            $locale = reset($locales);
        }
        $locale = $this->convertLocale($locale);
        $this->referenceResolver = $referenceResolver;
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
        $identifier = trim($this->getReferenceIdentifier($identifier));

        /*
        // original idea: regexp-based parsing
        // supported:
        //   someOperator
        //   someOperator(a, b)
        //   someOperator|filter
        //   someOperator(a, b)|filter
        //   someOperator(a, b)|filter(c, d)
        /// @todo this way of matching args does not allow for arguments which contain a comma, right parenthesis or pipe chars...
        if (!preg_match('/^([^(|]+)(?:\(([^)]+)\))?(?:\|(unique|optional|valid)(?:\(([^)]+)\))?)?$/', $identifier, $matches)) {
            throw new \Exception("Could not parse as faker identifier ths string '$identifier'.");
        }

        $formatter = trim($matches[1]);
        $args = isset($matches[2]) ? trim($matches[2]) : '';
        $filter = isset($matches[3]) ? trim($matches[3]) : '';
        $filterArgs = isset($matches[4]) ? trim($matches[4]) : '';
        if ($args != '')
        {
            /// @todo convert true, false to bools, strip strings of quotes
            $arguments = array_map('trim', explode(',', $args));
        } else {
            $arguments = array();
        }
        if ($filterArgs != '')
        {
            /// @todo convert true, false to bools, strip strings of quotes
            $filterArguments = array_map('trim', explode(',', $filterArgs));
        }
        else {
            $filterArguments = array();
        }

        $faker = $this->faker;

        if ($filter != '') {
            $faker = $this->faker->format($filter, $filterArguments);
        }

        return $faker->format($formatter, $arguments);
        */

        // alternative idea: use symfony/expression-language
        $expressionLanguage = new ExpressionLanguage();

        $resolver = $this->referenceResolver;

        $expressionLanguage->register(
            'resolve',
            function ($str) {
                /// @todo we could implement this via eg a static class var which holds a pointer to $this->referenceResolver
                //return sprintf('(is_string(%1$s) ? FakerResolver::resolveExpressionLanguageReference(%1$s) : %1$s)', $str);
                return "throw new \Exception('The \'resolve\' expression language operator can not be compiled, only evaluated'";
            },
            function ($arguments, $str) use ($resolver) {
                if (!is_string($str)) {
                    return $str;
                }

                return $resolver->resolveReference($str);
            }
        );

        return $expressionLanguage->evaluate('faker.' . $identifier, array(
            'faker' => $this->faker,
        ));
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
