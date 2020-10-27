<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Handler;

use Ergonode\Core\Domain\Command\UpdateLanguageCommand;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class UpdateLanguageCommandHandler
{
    /**
     * @var LanguageRepositoryInterface;
     */
    private LanguageRepositoryInterface $repository;

    private LanguageQueryInterface $query;

    public function __construct(LanguageRepositoryInterface $repository, LanguageQueryInterface $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    public function __invoke(UpdateLanguageCommand $command): void
    {
        $activeLanguages = $command->getLanguages();
        $allLanguages = $this->query->getAll();
        foreach ($allLanguages as $language) {
            $hasCode = $this->hasCode($language, $activeLanguages);
            $this->repository->save($language, $hasCode);
        }
    }

    /**
     * @param array $languages
     */
    private function hasCode(Language $search, array $languages): bool
    {
        foreach ($languages as $language) {
            if ($search->isEqual($language)) {
                return true;
            }
        }

        return false;
    }
}
