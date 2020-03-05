<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 */
class SimpleMultimediaFileProvider implements MultimediaFileProviderInterface
{
    private const PATH = '%s/public/multimedia/%s.%s';

    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param Multimedia $multimedia
     *
     * @return string
     */
    public function getFile(Multimedia $multimedia): string
    {
        return \sprintf(
            self::PATH,
            $this->kernel->getProjectDir(),
            $multimedia->getId()->getValue(),
            $multimedia->getExtension()
        );
    }
}
