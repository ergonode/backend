<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use JMS\Serializer\Annotation as JMS;

class DeleteImportCommand implements ImporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ImportId")
     */
    private ImportId $id;

    public function __construct(ImportId $id)
    {
        $this->id = $id;
    }

    public function getId(): ImportId
    {
        return $this->id;
    }
}
