<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Command\ExportProfile;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var ExportProfileId
     *
     * @JMS\Type("Ergonode\Exporter\Domain\Entity\Profile\ExportProfileId")
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
     * CreateExportProfileCommand constructor.
     * @param string $name
     * @param string $type
     * @param array  $parameters
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $type, array $parameters)
    {
        $this->id = ExportProfileId::generate();
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
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
