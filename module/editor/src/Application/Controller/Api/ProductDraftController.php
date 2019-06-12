<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Application\Controller\Api;

use Ergonode\Attribute\Domain\Provider\AttributeValidationProvider;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Application\Form\DraftCreateForm;
use Ergonode\Editor\Application\Model\DraftCreateFormModel;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Command\CreateProductDraftCommand;
use Ergonode\Editor\Domain\Command\PersistProductDraftCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Editor\Domain\Query\DraftQueryInterface;
use Ergonode\Editor\Domain\Query\ProductTemplateQueryInterface;
use Ergonode\Editor\Infrastructure\Grid\ProductDraftGrid;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Product\Domain\Entity\ProductId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

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
     * @var ProductTemplateQueryInterface
     */
    private $draftTemplateQuery;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var AttributeValidationProvider
     */
    private $provider;

    /**
     * @param ProductDraftGrid              $productDraftGrid
     * @param DraftQueryInterface           $draftQuery
     * @param ProductTemplateQueryInterface $draftTemplateQuery
     * @param MessageBusInterface           $messageBus
     * @param AttributeValidationProvider   $provider
     */
    public function __construct(
        ProductDraftGrid $productDraftGrid,
        DraftQueryInterface $draftQuery,
        ProductTemplateQueryInterface $draftTemplateQuery,
        MessageBusInterface $messageBus,
        AttributeValidationProvider $provider
    ) {
        $this->productDraftGrid = $productDraftGrid;
        $this->draftQuery = $draftQuery;
        $this->draftTemplateQuery = $draftTemplateQuery;
        $this->messageBus = $messageBus;
        $this->provider = $provider;
    }

    /**
     * @Route("/drafts", methods={"GET"})
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
        $pagination = $pagination = new RequestGridConfiguration($request);
        $result = $this->productDraftGrid->render($this->draftQuery->getDataSet(), $pagination, $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("/drafts/{draft}", methods={"GET"})
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
     * @Route("/drafts", methods={"POST"})
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
     * @Route("/drafts/{draft}/persist", methods={"PUT"})
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
     * @SWG\Response(
     *     response=201,
     *     description="Apply draft changes to product",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param ProductDraft $draft
     *
     * @ParamConverter(class="Ergonode\Editor\Domain\Entity\ProductDraft")
     *
     * @return Response
     */
    public function applyDraft(ProductDraft $draft): Response
    {
        $command = new PersistProductDraftCommand($draft->getId());

        $this->messageBus->dispatch($command);

        return $this->createRestResponse([], [], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/drafts/{draft}/{attribute}/value", methods={"PUT"})
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="draft",
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
     * @param ProductDraft      $draft
     * @param Language          $language
     * @param AbstractAttribute $attribute
     * @param Request           $request
     *
     * @ParamConverter(class="Ergonode\Editor\Domain\Entity\ProductDraft")
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @return Response
     */
    public function changeDraftAttribute(ProductDraft $draft, Language $language, AbstractAttribute $attribute, Request $request): Response
    {
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
     * @Route("/drafts/product/{product}", methods={"GET"})
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
     *
     * @param string   $product
     * @param Language $language
     *
     * @return Response
     * @throws \Exception
     */
    public function getProductDraft(string $product, Language $language): Response
    {
        $draftId = $this->draftQuery->getActualDraftId(new ProductId($product));
        if (null === $draftId) {
            $command = new CreateProductDraftCommand(new productId($product));

            $this->messageBus->dispatch($command);
            $draftId = $command->getId();
        }

        $result = $this->draftQuery->getDraftView($draftId, $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("/drafts/{draft}/template", methods={"GET"})
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="draft",
     *     in="path",
     *     type="string",
     *     description="Draft id",
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
    public function getDraftTemplate(string $draft, Language $language): Response
    {
        $result = $this->draftTemplateQuery->getTemplateView(new ProductDraftId($draft), $language);

        return $this->createRestResponse($result);
    }
}
