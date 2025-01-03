<?php

namespace Izopi4a\EcontBundle;

use Izopi4a\EcontBundle\DependencyInjection\EcontBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EcontBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): ?EcontBundleExtension
    {
        return new EcontBundleExtension();
    }
}