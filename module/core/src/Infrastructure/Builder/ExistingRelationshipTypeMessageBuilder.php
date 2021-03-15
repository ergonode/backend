<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Builder;

use Ergonode\Core\Infrastructure\Model\Relationship;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExistingRelationshipTypeMessageBuilder implements ExistingRelationshipMessageBuilderInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function build(Relationship $relationship): string
    {
        $messages = [];

        foreach ($relationship as $group) {
            $messages[] = $this->translator->trans(
                $group->getMessage(),
                ['%relations%' => implode(', ', $group->getRelations())]
            );
        }

        return implode(',', $messages);
    }
}
