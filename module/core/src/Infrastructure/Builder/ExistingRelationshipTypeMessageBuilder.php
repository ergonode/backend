<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Builder;

use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ExistingRelationshipTypeMessageBuilder implements ExistingRelationshipMessageBuilderInterface
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function build(RelationshipCollection $relationshipCollection): string
    {
        $relationships = $this->groupByClass($relationshipCollection);
        foreach ($relationships as &$relationship) {
            $relationship = $this->convertClassToTranslation($relationship);
        }

        return $this->translator->trans(
            'Element has active relationships with %relationships%',
            ['%relationships%' => implode(', ', $relationships)]
        );
    }

    /**
     * @param RelationshipCollection $relationshipCollection
     *
     * @return array
     */
    private function groupByClass(RelationshipCollection $relationshipCollection): array
    {
        $classCollection = [];
        /** @var AggregateId $item */
        foreach ($relationshipCollection as $item) {
            $class = get_class($item);
            if (!in_array($class, $classCollection, true)) {
                $classCollection[] = $class;
            }
        }

        return $classCollection;
    }

    /**
     * @param string $class
     *
     * @return string
     *
     * @todo This is evil! rprzedzik, we need to discuss it, because this very bad hax
     */
    private function convertClassToTranslation(string $class): string
    {
        $key = substr($class, strrpos($class, '\\') + 1, strlen($class));
        $key = str_replace('Id', '', $key);

        return $this->translator->trans(strtolower($key));
    }
}
