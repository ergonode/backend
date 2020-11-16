<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Application\Controller\Api;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\ViewTemplateBuilder;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Command\RemoveProductAttributeValueCommand;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;
use Ramsey\Uuid\Uuid;

class ProductDraftController extends AbstractController
{
    private CommandBusInterface $commandBus;

    private AttributeValueConstraintProvider $provider;

    private ViewTemplateBuilder $builder;

    private ValidatorInterface $validator;

    private TemplateRepositoryInterface $templateRepository;

    private TranslationInheritanceCalculator $calculator;

    private LanguageQueryInterface $query;

    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        AttributeValueConstraintProvider $provider,
        ViewTemplateBuilder $builder,
        ValidatorInterface $validator,
        TemplateRepositoryInterface $templateRepository,
        TranslationInheritanceCalculator $calculator,
        LanguageQueryInterface $query,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->commandBus = $commandBus;
        $this->provider = $provider;
        $this->builder = $builder;
        $this->validator = $validator;
        $this->templateRepository = $templateRepository;
        $this->calculator = $calculator;
        $this->query = $query;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @Route("/products/{product}/draft/persist", methods={"PUT"} ,requirements={"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("PRODUCT_UPDATE")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Apply draft changes to product",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function applyDraft(AbstractProduct $product): Response
    {
        //@todo remove after frontend changes

        return new EmptyResponse();
    }

    /**
     * @Route(
     *     "/products/{product}/draft/{attribute}/value",
     *     methods={"PUT"},
     *     requirements = {
     *        "product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
     *        "attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
     *     }
     * )
     *
     * @IsGranted("PRODUCT_UPDATE")
     * @IsGranted("edit", subject="language")
     *
     * @SWG\Tag(name="Editor")
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
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
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
        AbstractProduct $product,
        Language $language,
        AbstractAttribute $attribute,
        Request $request
    ): Response {
        $value = $request->request->get('value');
        $value = $value !== '' ? $value : null;

        $constraint = $this->provider->provide($attribute);
        if ($attribute->getScope()->isGlobal()) {
            $root = $this->query->getRootLanguage();
            if (!$root->isEqual($language)) {
                throw new AccessDeniedHttpException();
            }
        }

        $violations = $this->validator->validate(['value' => $value], $constraint);
        if (0 === $violations->count()) {
            $command = new ChangeProductAttributeValueCommand($product->getId(), $attribute->getId(), $language, $value);
            $this->commandBus->dispatch($command);

            return new SuccessResponse(['value' => $value]);
        }

        throw new ViolationsHttpException($violations);
    }

    /**
     * @Route(
     *     "/products/{product}/draft/{attribute}/value",
     *     methods={"DELETE"},
     *     requirements = {
     *        "product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
     *        "attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
     *     }
     * )
     *
     * @IsGranted("PRODUCT_UPDATE")
     * @IsGranted("edit", subject="language")
     *
     * @SWG\Tag(name="Editor")
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
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Change product attribute Value",
     * )
     *
     * @throws \Exception
     */
    public function removeDraftAttribute(
        AbstractProduct $product,
        Language $language,
        AbstractAttribute $attribute
    ): Response {
        if ($attribute->getScope()->isGlobal()) {
            $root = $this->query->getRootLanguage();
            if (!$root->isEqual($language)) {
                throw new AccessDeniedHttpException();
            }
        }
        $command = new RemoveProductAttributeValueCommand($product->getId(), $attribute->getId(), $language);
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }

    /**
     * @Route("/products/{product}/draft", methods={"GET"} ,requirements={"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Get product draft",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return product draft model",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function getProductDraft(Language $language, AbstractProduct $product): Response
    {
        $result = [
            'id' => Uuid::uuid4()->toString(), //@todo remove after frontend changes
            'product_id' => $product->getId()->getValue(),
        ];
        $value = null;
        foreach ($product->getAttributes() as $key => $value) {
            $attributeId = AttributeId::fromKey($key);
            $attribute = $this->attributeRepository->load($attributeId);
            $result['attributes'][$key] = $this->calculator->calculate($attribute, $value, $language);
        }

        return new SuccessResponse($result);
    }

    /**
     * @Route("/products/{product}/template", methods={"GET"} ,requirements={"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Get product draft",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return product template model",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function getProductTemplate(AbstractProduct $product, Language $language): Response
    {
        $templateId = $product->getTemplateId();

        $template = $this->templateRepository->load($templateId);

        Assert::notNull($template);

        $view = $this->builder->build($template, $language);

        return new SuccessResponse($view);
    }
}
