<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Validator;

use Ergonode\Core\Application\Model\LanguageTree\LanguageTreeNodeFormModel;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\Query\LanguageTreeQueryInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LanguageTreeLanguageRelationValidator extends ConstraintValidator
{
    private LanguageTreeQueryInterface $languageTreeQuery;

    private LanguageQueryInterface $languageQuery;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        LanguageTreeQueryInterface $languageTreeQuery,
        LanguageQueryInterface $languageQuery,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->languageTreeQuery = $languageTreeQuery;
        $this->languageQuery = $languageQuery;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LanguageTreeLanguageRelation) {
            throw new UnexpectedTypeException($constraint, LanguageTreeLanguageRelation::class);
        }

        if (!$value instanceof LanguageTreeNodeFormModel) {
            throw new UnexpectedValueException($value, LanguageTreeNodeFormModel::class);
        }

        $deleted = array_diff($this->getCurrent(), $this->createArray($value));

        if ($deleted) {
            $relations = [];
            foreach ($deleted as $languageId) {
                $relation = $this->relationshipsResolver->resolve($languageId);

                if ($relation) {
                    $relations[] = $languageId;
                }
            }
            if ($relations) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter(
                        '{{ languages }}',
                        implode(', ', $this->languageQuery->getLanguagesByIds($relations))
                    )
                    ->addViolation();
            }
        }
    }

    /**
     * @return LanguageId[]
     */
    private function getCurrent(): array
    {
        $result = [];
        $data = $this->languageTreeQuery->getTree();
        foreach ($data as $row) {
            $result[] = new LanguageId($row['id']);
        }

        return $result;
    }

    /**
     * @return LanguageId[]
     */
    private function createArray(LanguageTreeNodeFormModel $languages): array
    {
        $children = [];
        if ($languages->languageId && LanguageId::isValid($languages->languageId)) {
            $children = [new LanguageId($languages->languageId)];
        }

        foreach ($languages->children as $child) {
            $children = array_merge($children, $this->createArray($child));
        }

        return $children;
    }
}
