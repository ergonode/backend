<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\FormatterInterface;

/**
 */
class CreateReaderCommand implements DomainCommandInterface
{
    /**
     * @var ReaderId
     */
    private ReaderId $id;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string[]
     */
    private array $configuration;

    /**
     * @var FormatterInterface[]
     */
    private array $formatters;


    /**
     * @param string $name
     * @param string $type
     * @param array  $configuration
     * @param array  $formatters
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $type, array $configuration = [], array $formatters = [])
    {
        $this->id = ReaderId::generate();
        $this->type = $type;
        $this->name = $name;
        $this->configuration = $configuration;
        $this->formatters = $formatters;
    }

    /**
     * @return ReaderId
     */
    public function getId(): ReaderId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return FormatterInterface[]
     */
    public function getFormatters(): array
    {
        return $this->formatters;
    }
}
