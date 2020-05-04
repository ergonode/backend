<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Command\LanguageTree;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateLanguageTreeCommand implements DomainCommandInterface
{
    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $rootLanguage;

    /**
     * @param Language $rootLanguage
     */
    public function __construct(Language $rootLanguage)
    {
        $this->rootLanguage = $rootLanguage;
    }

    /**
     * @return Language
     */
    public function getRootLanguage(): Language
    {
        return $this->rootLanguage;
    }
}
