<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Mapper\LanguageMapper;

/**
 */
class LanguageProvider implements LanguageProviderInterface
{
    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @var LanguageMapper
     */
    private LanguageMapper $mapper;

    /**
     * @param LanguageQueryInterface $query
     * @param LanguageMapper         $mapper
     */
    public function __construct(LanguageQueryInterface $query, LanguageMapper $mapper)
    {
        $this->query = $query;
        $this->mapper = $mapper;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getLanguages(Language $language): array
    {
        return $this->mapper->map($language, $this->query->getAll());
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getActiveLanguages(Language $language): array
    {
        return $this->mapper->map($language, $this->query->getActive());
    }
}
