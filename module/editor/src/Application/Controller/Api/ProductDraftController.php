<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use Ergonode\Editor\Domain\Command\PersistProductDraftCommand;
use Ergonode\Editor\Domain\Command\RemoveProductAttributeValueCommand;
use Ergonode\Editor\Domain\Provider\DraftProvider;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 */
class ProductDraftController extends AbstractController
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var AttributeValueConstraintProvider
     */
    private AttributeValueConstraintProvider $provider;

    /**
     * @var DraftProvider
     */
    private DraftProvider $draftProvider;

    /**
     * @var ViewTemplateBuilder
     */
    private ViewTemplateBuilder $builder;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $templateRepository;

    /**
     * @var TranslationInheritanceCalculator
     */
    private TranslationInheritanceCalculator $calculator;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param CommandBusInterface              $commandBus
     * @param AttributeValueConstraintProvider $provider
     * @param DraftProvider                    $draftProvider
     * @param ViewTemplateBuilder              $builder
     * @param ValidatorInterface               $validator
     * @param TemplateRepositoryInterface      $templateRepository
     * @param TranslationInheritanceCalculator $calculator
     * @param LanguageQueryInterface           $query
     * @param AttributeRepositoryInterface     $attributeRepository
     */
    public function __construct(
        CommandBusInterface $commandBus,
        AttributeValueConstraintProvider $provider,
        DraftProvider $draftProvider,
        ViewTemplateBuilder $builder,
        ValidatorInterface $validator,
        TemplateRepositoryInterface $templateRepository,
        TranslationInheritanceCalculator $calculator,
        LanguageQueryInterface $query,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->commandBus = $commandBus;
        $this->provider = $provider;
        $this->draftProvider = $draftProvider;
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
     * @param AbstractProduct $product
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function applyDraft(AbstractProduct $product): Response
    {
        $draft = $this->draftProvider->provide($product);

        $command = new PersistProductDraftCommand($draft->getId());
        $this->commandBus->dispatch($command);

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
     * @param AbstractProduct   $product
     * @param Language          $language
     * @param AbstractAttribute $attribute
     * @param Request           $request
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function changeDraftAttribute(
        AbstractProduct $product,
        Language $language,
        AbstractAttribute $attribute,
        Request $request
    ): Response {
        $draft = $this->draftProvider->provide($product);
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
            $command = new ChangeProductAttributeValueCommand($draft->getId(), $attribute->getId(), $language, $value);
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
     * @param AbstractProduct   $product
     * @param Language          $language
     * @param AbstractAttribute $attribute
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function removeDraftAttribute(
        AbstractProduct $product,
        Language $language,
        AbstractAttribute $attribute
    ): Response {
        $draft = $this->draftProvider->provide($product);
        if ($attribute->getScope()->isGlobal()) {
            $root = $this->query->getRootLanguage();
            if (!$root->isEqual($language)) {
                throw new AccessDeniedHttpException();
            }
        }
        $command = new RemoveProductAttributeValueCommand($draft->getId(), $attribute->getId(), $language);
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
     * @param Language        $language
     * @param AbstractProduct $product
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function getProductDraft(Language $language, AbstractProduct $product): Response
    {
        $draft = $this->draftProvider->provide($product);

        $result = [
            'id' => $draft->getId()->getValue(),
            'product_id' => $draft->getProductId()->getValue(),
        ];
        $value = null;
        foreach ($draft->getAttributes() as $key => $value) {
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
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
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
