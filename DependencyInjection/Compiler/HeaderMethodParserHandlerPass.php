<?php

namespace Ez\RestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HeaderMethodParserHandlerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        //check if service exist
        if (!$container->has('ez_rest.request_data_parser.service')) {
            return;
        }
        //retrive the service definition
        $definition = $container->getDefinition('ez_rest.request_data_parser.service');

        //find all services tagged with "ez_rest.request_data.contenttype_contentlist"
        $taggedServices = $container->findTaggedServiceIds('ez_rest.request_data.contenttype_contentlist');

        $responseMethodParser = [];
        //loop through the taggedServices and pass a reference to each notification into it.
        foreach ($taggedServices as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['alias'])) {
                    throw new \LogicException('ez_rest.request_data_parser.service tag needs an alias to identify the parser. None given.');
                }
                $responseMethodParser[$attribute['alias']] = new Reference($id);
            }
        }
        $definition->addMethodCall('getRequest', [$responseMethodParser]);
    }
}
