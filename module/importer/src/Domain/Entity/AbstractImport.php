<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity;

use Ergonode\Importer\Domain\ValueObject\ImportStatus;

/**
 */
abstract class AbstractImport implements \JsonSerializable
{
    /**
     * @var ImportId;
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ImportStatus
     */
    protected $status;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param ImportId $id
     * @param string   $name
     */
    public function __construct(ImportId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = new ImportStatus(ImportStatus::CREATED);
    }

    /**
     * @return ImportId
     */
    public function getId(): ImportId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ImportStatus
     */
    public function getStatus(): ImportStatus
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     */
    public function process(): void
    {
        if (!$this->getStatus()->isCreated()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::PRECESSED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::PRECESSED);
    }

    /**
     * @param string|null $reason
     */
    public function stop(string $reason = null): void
    {
        if ($this->getStatus()->isStopped()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::STOPPED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::STOPPED);
        $this->reason = $reason;
    }

    /**
     */
    public function end(): void
    {
        if (!$this->getStatus()->isProcessed()) {
            throw new \LogicException(
                \sprintf('Can\'t change status to %s from %s', ImportStatus::ENDED, $this->getStatus())
            );
        }

        $this->status = new ImportStatus(ImportStatus::ENDED);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId()->getValue(),
            'name' => $this->getName(),
            'type' => static::class,
            'options' => $this->getOptions(),
        ];
    }

    /**
     * @return string
     */
    abstract public function getType(): string;
}
