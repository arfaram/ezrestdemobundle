<?php

namespace Ez\RestBundle;

use Ez\RestBundle\DependencyInjection\Compiler\HeaderMethodParserHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzRestBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HeaderMethodParserHandlerPass());
    }
}
