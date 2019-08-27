<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Application\Controller\Api;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeValidationProvider;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\ViewTemplateBuilder;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Editor\Application\Form\DraftCreateForm;
use Ergonode\Editor\Application\Model\DraftCreateFormModel;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Command\CreateProductDraftCommand;
use Ergonode\Editor\Domain\Command\PersistProductDraftCommand;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Editor\Domain\Provider\DraftProvider;
use Ergonode\Editor\Domain\Query\DraftQueryInterface;
use Ergonode\Editor\Infrastructure\Grid\ProductDraftGrid;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 */
class ProductDraftController extends AbstractApiController
{
    /**
     * @var ProductDraftGrid
     */
    private $productDraftGrid;

    /**
     * @var DraftQueryInterface
     */
    private $draftQuery;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var AttributeValidationProvider
     */
    private $provider;

    /**
     * @var DraftProvider
     */
    private $draftProvider;

    /**
     * @var ViewTemplateBuilder
     */
    private $builder;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @param ProductDraftGrid            $productDraftGrid
     * @param DraftQueryInterface         $draftQuery
     * @param MessageBusInterface         $messageBus
     * @param AttributeValidationProvider $provider
     * @param DraftProvider               $draftProvider
     * @param ViewTemplateBuilder         $builder
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        ProductDraftGrid $productDraftGrid,
        DraftQueryInterface $draftQuery,
        MessageBusInterface $messageBus,
        AttributeValidationProvider $provider,
        DraftProvider $draftProvider,
        ViewTemplateBuilder $builder,
        TemplateRepositoryInterface $templateRepository
    ) {
        $this->productDraftGrid = $productDraftGrid;
        $this->draftQuery = $draftQuery;
        $this->messageBus = $messageBus;
        $this->provider = $provider;
        $this->draftProvider = $draftProvider;
        $this->builder = $builder;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @Route("/products/drafts", methods={"GET"})
     *
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id", "label","code", "hint"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Get draft grid",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getDrafts(Language $language, Request $request): Response
    {
        $configuration = new RequestGridConfiguration($request);
        $result = $this->renderGrid($this->productDraftGrid, $configuration, $this->draftQuery->getDataSet(), $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("/products/{draft}", methods={"GET"}, requirements={"draft" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="draft",
     *     in="path",
     *     type="string",
     *     description="Product draft id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Change draft",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param string   $draft
     * @param Language $language
     *
     * @return Response
     */
    public function getDraft(string $draft, Language $language): Response
    {
        $result = $this->draftQuery->getDraftView(new ProductDraftId($draft), $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("/products/drafts", methods={"POST"})
     *
     * @IsGranted("PRODUCT_CREATE")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="productId",
     *     in="formData",
     *     type="string",
     *     description="Product id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Create product draft based on product id",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createDraft(Request $request): Response
    {
        $model = new DraftCreateFormModel();

        $form = $this->createForm(DraftCreateForm::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DraftCreateFormModel $data */
            $data = $form->getData();
            $command = new CreateProductDraftCommand(new productId($data->productId));
            $this->messageBus->dispatch($command);

            return $this->createRestResponse(['id' => $command->getId()->getValue()], [], Response::HTTP_CREATED);
        }

        return $this->createRestResponse($form);
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
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Apply draft changes to product",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param AbstractProduct $product
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @return Response
     * @throws \Exception
     */
    public function applyDraft(AbstractProduct $product): Response
    {
        $draft = $this->draftProvider->provide($product);

        $command = new PersistProductDraftCommand($draft->getId());

        $this->messageBus->dispatch($command);

        return $this->createRestResponse([], [], Response::HTTP_ACCEPTED);
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
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product draft id",
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
     *     default="EN",
     *     description="Language Code",
     * )
     * )
     * @SWG\Parameter(
     *     name="value",
     *     in="formData",
     *     type="string",
     *     description="Attribute value",
     *     required=true,
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Change product attribute Value",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
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
    public function changeDraftAttribute(AbstractProduct $product, Language $language, AbstractAttribute $attribute, Request $request): Response
    {
        $draft = $this->draftProvider->provide($product);

        $value = $request->request->get('value');

        $validator = $this->provider->provide($attribute);

        if (null !== $value && '' !== $value) {
            if ($validator->isValid($attribute, $value)) {
                $command = new ChangeProductAttributeValueCommand($draft->getId(), $attribute->getId(), $language, $value);
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['value' => $value], [], Response::HTTP_ACCEPTED);
            }
        } else {
            $command = new ChangeProductAttributeValueCommand($draft->getId(), $attribute->getId(), $language);
            $this->messageBus->dispatch($command);

            return $this->createRestResponse(['value' => $value], [], Response::HTTP_ACCEPTED);
        }

        $result = [
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Form validation error',
            'errors' => [
                'value' => [\sprintf('%s is incorrect value for %s attribute', $value, $attribute->getType())],
            ],
        ];

        return $this->createRestResponse($result, [], Response::HTTP_BAD_REQUEST);
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
     *     default="EN",
     *     description="Language Code",
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Change draft",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     */
    public function getProductDraft(AbstractProduct $product, Language $language): Response
    {
        $draft = $this->draftProvider->provide($product);

        return $this->createRestResponse($draft);
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
     *     default="EN",
     *     description="Language Code",
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Change draft",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
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
        $template = $this->templateRepository->load($product->getTemplateId());

        Assert::notNull($template);

        $view = $this->builder->build($template, $language);

        return $this->createRestResponse($view);
    }
}
