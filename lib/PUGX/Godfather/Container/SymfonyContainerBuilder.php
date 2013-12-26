<?php

namespace PUGX\Godfather\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyContainerBuilder implements ContainerInterface
{
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->container, $method), $args);
    }

    /**
     * {@inheritDoc}
     */
    public function setAlias($alias, $id)
    {
        return $this->container->setAlias($alias, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function set($id, $service)
    {
        return $this->container->set($id, $service);
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
}
