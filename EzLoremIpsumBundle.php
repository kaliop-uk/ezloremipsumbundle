<?php

namespace Kaliop\eZLoremIpsumBundle;

use Kaliop\eZLoremIpsumBundle\DependencyInjection\CompilerPass\InjectResolversCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzLoremIpsumBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TaggedServicesCompilerPass());
    }
}
