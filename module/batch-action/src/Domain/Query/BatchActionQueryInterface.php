<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Query;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Model\BatchActionInformationModel;
use Ergonode\Core\Domain\ValueObject\Language;

interface BatchActionQueryInterface
{
    public function getInformation(BatchActionId $id, Language $language): BatchActionInformationModel;

    public function getProfileInfo(): array;
}
