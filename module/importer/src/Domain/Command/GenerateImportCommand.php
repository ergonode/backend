<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Application\Model\Form\ConfigurationModel;
use Ergonode\Importer\Domain\Entity\ImportId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class GenerateImportCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private ImportId $id;

    /**
     * @var ConfigurationModel
     */
    private ConfigurationModel $configuration;

    /**
     * @param ImportId           $id
     * @param ConfigurationModel $configuration
     *
     * @throws \Exception
     */
    public function __construct(ImportId $id, ConfigurationModel $configuration)
    {
        $this->id = $id;
        $this->configuration = $configuration;
    }

    /**
     * @return ImportId
     */
    public function getId(): ImportId
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
