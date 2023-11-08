<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractKernelTestCase extends KernelTestCase
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->container = static::getContainer();
    }

    protected function testContainerBoot(): void
    {
        $this->assertNotNull($this->container);
        $this->assertInstanceOf(ContainerInterface::class, $this->container);
    }

}
