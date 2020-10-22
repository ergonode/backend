<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Command\LanguageTree;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use JMS\Serializer\Annotation as JMS;

class CreateLanguageTreeCommand implements DomainCommandInterface
{
    /**
     * @var LanguageId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\LanguageId")
     */
    private LanguageId $rootLanguage;

    /**
     * @param LanguageId $rootLanguage
     */
    public function __construct(LanguageId $rootLanguage)
    {
        $this->rootLanguage = $rootLanguage;
    }

    /**
     * @return LanguageId
     */
    public function getRootLanguage(): LanguageId
    {
        return $this->rootLanguage;
    }
}
