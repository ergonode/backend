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
class DeleteExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    private ExportProfileId $id;

    /**
     * @param ExportProfileId $id
     */
    public function __construct(ExportProfileId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ExportProfileId
     */
    public function getId(): ExportProfileId
    {
        return $this->id;
    }
}
