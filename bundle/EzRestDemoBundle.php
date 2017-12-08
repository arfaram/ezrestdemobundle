<?php

namespace Ez\RestDemoBundle;

use Ez\RestDemoBundle\DependencyInjection\Compiler\HeaderMethodParserHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzRestDemoBundle extends Bundle
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
