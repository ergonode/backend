<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Mapper\LanguageMapper;

class LanguageProvider implements LanguageProviderInterface
{
    private LanguageQueryInterface $query;

    private LanguageMapper $mapper;

    public function __construct(LanguageQueryInterface $query, LanguageMapper $mapper)
    {
        $this->query = $query;
        $this->mapper = $mapper;
    }

    /**
     * @return array
     */
    public function getLanguages(Language $language): array
    {
        return $this->mapper->map($language, $this->query->getAll());
    }

    /**
     * @return array
     */
    public function getActiveLanguages(Language $language): array
    {
        return $this->mapper->map($language, $this->query->getActive());
    }
}
