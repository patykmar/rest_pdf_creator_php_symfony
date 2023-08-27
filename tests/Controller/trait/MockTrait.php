<?php

namespace App\Tests\Controller\trait;

use PHPUnit\Framework\MockObject\MockObject;

trait MockTrait
{
    protected function injectMockToSymfonyContext(string $className, object $mockObject): void
    {
        $container = static::getContainer();
        $container->set($className, $mockObject);
    }

    protected function expectOnceRunMethodWithAnyParameters(MockObject $mockObject, string $methodName): void
    {
        $mockObject
            ->expects($this->once())
            ->method($methodName)
            ->withAnyParameters();
    }

    protected function methodWithAnyParameterWillReturnObject(MockObject $mockObject, string $methodName, object $returnObject): void
    {
        $mockObject
            ->method($methodName)
            ->withAnyParameters()
            ->willReturn($returnObject);
    }
}
