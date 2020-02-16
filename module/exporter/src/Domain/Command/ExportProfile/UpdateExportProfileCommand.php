<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Command\ExportProfile;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class UpdateExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    private ExportProfileId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private array $parameters;

    /**
     * UpdateExportProfileCommand constructor.
     *
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $type
     * @param array           $parameters
     */
    public function __construct(ExportProfileId $id, string $name, string $type, array $parameters)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->parameters = $parameters;
    }

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
