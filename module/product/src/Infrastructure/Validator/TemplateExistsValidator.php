<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class TemplateExistsValidator extends ConstraintValidator
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(TemplateRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TemplateExists) {
            throw new UnexpectedTypeException($constraint, TemplateExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        try {
            $template = $this->templateRepository->load(new TemplateId($value));
        } catch (\Exception $exception) {
            $template = false;
        }

        if (!$template) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
