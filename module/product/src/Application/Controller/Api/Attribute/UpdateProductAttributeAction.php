<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Attribute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Ergonode\Product\Domain\Command\Attribute\ChangeProductAttributeCommand;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

class UpdateProductAttributeAction
{
    private CommandBusInterface $commandBus;

    private AttributeValueConstraintProvider $provider;

    private ValidatorInterface $validator;

    private LanguageQueryInterface $query;

    public function __construct(
        CommandBusInterface $commandBus,
        AttributeValueConstraintProvider $provider,
        ValidatorInterface $validator,
        LanguageQueryInterface $query
    ) {
        $this->commandBus = $commandBus;
        $this->provider = $provider;
        $this->validator = $validator;
        $this->query = $query;
    }

    /**
     * @Route(
     *     "/products/{product}/attribute/{attribute}",
     *     methods={"PUT"},
     *     requirements = {
     *        "product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
     *        "attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
     *     }
     * )
     *
     * @IsGranted("PRODUCT_ATTRIBUTE_PUT")
     * @IsGranted("edit", subject="language")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product id",
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
     *     description="Change product attribute Value",
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
        AbstractProduct $product,
        AbstractAttribute $attribute,
        Request $request
    ): Response {
        $value = $request->request->get('value');
        $value = $value === '' ? null : $value;

        $constraint = $this->provider->provide($attribute);
        if ($attribute->getScope()->isGlobal()) {
            $root = $this->query->getRootLanguage();
            if (!$root->isEqual($language)) {
                throw new AccessDeniedHttpException();
            }
        }

        $violations = $this->validator->validate(['value' => $value], $constraint);
        if (0 === $violations->count()) {
            $command = new ChangeProductAttributeCommand(
                $product->getId(),
                $attribute->getId(),
                $language,
                $value
            );
            $this->commandBus->dispatch($command);

            return new SuccessResponse(['value' => $value]);
        }

        throw new ViolationsHttpException($violations);
    }
}
