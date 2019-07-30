<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Provider;

use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\Generator\ReaderGenerator;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;

/**
 */
class ReaderProvider
{
    /**
     * @var ReaderRepositoryInterface
     */
    private $repository;

    /**
     * @var ReaderGenerator
     */
    private $generator;

    /**
     * @param ReaderRepositoryInterface $repository
     * @param ReaderGenerator           $generator
     */
    public function __construct(ReaderRepositoryInterface $repository, ReaderGenerator $generator)
    {
        $this->repository = $repository;
        $this->generator = $generator;
    }

    /**
     * @param string $type
     *
     * @return Reader
     *
     * @throws \Exception
     */
    public function provide(string $type): Reader
    {
        $reader = $this->repository->load(ReaderId::fromValue($type));
        if (null === $reader) {
            $reader = $this->generator->generate($type);
            $this->repository->save($reader);
        }

        return $reader;
    }
}
