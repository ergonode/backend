<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Application\Model\Form\ConfigurationModel;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;

class GenerateImportCommand implements DomainCommandInterface
{
    /**
     * @var SourceId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SourceId")
     */
    private SourceId $id;

    /**
     * @var ConfigurationModel
     */
    private ConfigurationModel $configuration;

    /**
     * @param SourceId           $id
     * @param ConfigurationModel $configuration
     *
     * @throws \Exception
     */
    public function __construct(SourceId $id, ConfigurationModel $configuration)
    {
        $this->id = $id;
        $this->configuration = $configuration;
    }

    /**
     * @return SourceId
     */
    public function getId(): SourceId
    {
        return $this->id;
    }

    /**
     * @return ConfigurationModel
     */
    public function getConfiguration(): ConfigurationModel
    {
        return $this->configuration;
    }
}
