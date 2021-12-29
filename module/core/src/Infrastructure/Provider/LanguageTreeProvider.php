<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Core\Application\Security\Security;
use Ergonode\Core\Domain\Query\LanguageTreeQueryInterface;
use Ergonode\Core\Domain\User\LanguageCollectionAwareInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Mapper\LanguageTreeMapper;

class LanguageTreeProvider implements LanguageTreeProviderInterface
{
    private LanguageTreeQueryInterface $query;
    private Security $security;
    private LanguageTreeMapper $mapper;

    public function __construct(
        LanguageTreeQueryInterface $query,
        Security $security,
        LanguageTreeMapper $mapper
    ) {
        $this->query = $query;
        $this->security = $security;
        $this->mapper = $mapper;
    }

    /**
     * @return array
     */
    public function getActiveLanguages(Language $language): array
    {
        $user = $this->security->getUser();

        if (!$user instanceof LanguageCollectionAwareInterface) {
            return [];
        }
        $privileges = $user->getLanguagePrivilegesCollection();
        $tree = $this->query->getTree();

        return $this->mapper->map($language, $tree, $privileges);
    }
}
