<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Provider;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGenerator;

/**
 */
class TransformerProvider
{
    /**
     * @var TransformerGenerator
     */
    private $generator;

    /**
     * @var TransformerRepositoryInterface
     */
    private $repository;

    /**
     * @param TransformerGenerator           $generator
     * @param TransformerRepositoryInterface $repository
     */
    public function __construct(TransformerGenerator $generator, TransformerRepositoryInterface $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    /**
     * @param string $name
     * @param string $key
     *
     * @return Transformer
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function provide(string $name, string $key): Transformer
    {
        $id = TransformerId::fromKey($key);
        $transformer = $this->repository->load($id);
        if (null === $transformer) {
            $transformer = $this->generator->generate($name, $key);
            $this->repository->save($transformer);
        }

        return $transformer;
    }
}
