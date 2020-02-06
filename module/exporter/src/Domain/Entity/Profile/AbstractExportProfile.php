<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity\Profile;

use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
abstract class AbstractExportProfile
{
    /**
     * @var  ExportProfileId
     */
    protected ExportProfileId $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array $configuration
     */
    protected array $configuration;

    /**
     * AbstractExportProfile constructor.
     * @param ExportProfileId $id
     * @param string          $name
     */
    public function __construct(ExportProfileId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->configuration = [];
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return ExportProfileId
     */
    public function getId(): ExportProfileId
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
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
