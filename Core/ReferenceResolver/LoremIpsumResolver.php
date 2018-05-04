<?php

namespace Kaliop\eZLoremIpsumBundle\Core\ReferenceResolver;

use Kaliop\eZMigrationBundle\Core\ReferenceResolver\AbstractResolver;

class LoremIpsumResolver extends AbstractResolver
{
    protected $referencePrefixes = array('lipsum:');

    /**
     * @param string $identifier format: 'rand:x,y', 'lipsum:length'
     * @return mixed
     * @throws \Exception When trying to retrieve an unset reference
     */
    public function getReferenceValue($identifier)
    {
        $identifier = $this->getReferenceIdentifier($identifier);
        if (!array_key_exists($identifier, $this->references)) {
            throw new \Exception("No reference set with identifier '$identifier'");
        }

        return $this->references[$identifier];
    }
}