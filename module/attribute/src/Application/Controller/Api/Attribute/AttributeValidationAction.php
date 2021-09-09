<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\AggregateId;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AttributeValidationAction
{
    private AttributeValueConstraintProvider $provider;

    private ValidatorInterface $validator;

    private LanguageQueryInterface $query;

    public function __construct(
        AttributeValueConstraintProvider $provider,
        ValidatorInterface $validator,
        LanguageQueryInterface $query
    ) {
        $this->provider = $provider;
        $this->validator = $validator;
        $this->query = $query;
    }

    /**
     * @Route(
     *     name="ergonode_attribute_validate",
     *     path="/attribute/{attribute}/validate",
     *     methods={"POST"},
     *     requirements={
     *         "attribute"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
     *     }
     * )
     *
     * @IsGranted("ERGONODE_ROLE_ATTRIBUTE_POST_VALIDATION")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     * @SWG\Parameter(
     *     name="value",
     *     in="formData",
     *     type="string",
     *     description="Attribute value",
     *     required=true,
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Attribute value valid",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function changeDraftAttribute(
        Language $language,
        AbstractAttribute $attribute,
        Request $request
    ): array {
        $value = $request->request->get('value');
        $value = $value === '' ? null : $value;
        $aggregateId = null;
        if ($request->query->has('aggregateId')) {
            $aggregateIdValue = $request->query->get('aggregateId');
            try {
                $aggregateId = new AggregateId($aggregateIdValue);
            } catch (\InvalidArgumentException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        $constraint = $this->provider->provide($attribute, $aggregateId);

        if ($attribute->getScope()->isGlobal()) {
            $root = $this->query->getRootLanguage();
            if (!$root->isEqual($language)) {
                throw new AccessDeniedHttpException();
            }
        }

        $violations = $this->validator->validate(['value' => $value], $constraint);
        if (0 === $violations->count()) {
            return ['value' => $value];
        }

        throw new ViolationsHttpException($violations);
    }
}
