<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api;

use Ergonode\Attribute\Application\Form\AttributeCreateForm;
use Ergonode\Attribute\Application\Form\AttributeUpdateForm;
use Ergonode\Attribute\Application\Form\Model\CreateAttributeFormModel;
use Ergonode\Attribute\Application\Form\Model\UpdateAttributeFormModel;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\AttributeGridQueryInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Query\AttributeTemplateQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Infrastructure\Grid\AttributeGrid;
use Ergonode\Core\Application\Exception\FormValidationHttpException;
use Ergonode\Core\Application\Response\CreatedResponse;
use Ergonode\Core\Application\Response\EmptyResponse;
use Ergonode\Core\Application\Response\MethodNotAllowedResponse;
use Ergonode\Core\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class AttributeController extends AbstractController
{
    /**
     * @var AttributeGrid
     */
    private $attributeGrid;

    /**
     * @var AttributeQueryInterface
     */
    private $attributeQuery;

    /**
     * @var AttributeGridQueryInterface
     */
    private $attributeGridQuery;

    /**
     * @var AttributeTemplateQueryInterface
     */
    private $attributeTemplateQuery;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param AttributeGrid                   $attributeGrid
     * @param AttributeQueryInterface         $attributeQuery
     * @param AttributeGridQueryInterface     $attributeGridQuery
     * @param AttributeTemplateQueryInterface $attributeTemplateQuery
     * @param MessageBusInterface             $messageBus
     */
    public function __construct(
        AttributeGrid $attributeGrid,
        AttributeQueryInterface $attributeQuery,
        AttributeGridQueryInterface $attributeGridQuery,
        AttributeTemplateQueryInterface $attributeTemplateQuery,
        MessageBusInterface $messageBus
    ) {
        $this->attributeGrid = $attributeGrid;
        $this->attributeQuery = $attributeQuery;
        $this->attributeGridQuery = $attributeGridQuery;
        $this->attributeTemplateQuery = $attributeTemplateQuery;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/attributes", methods={"GET"})
     *
     * @IsGranted("ATTRIBUTE_READ")
     *
     * @SWG\Tag(name="Attribute")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute collection",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getAttributes(Language $language, Request $request): Response
    {
        $configuration = new RequestGridConfiguration($request);
        $dataSet = $this->attributeGridQuery->getDataSet($language);
        return new GridResponse($this->attributeGrid, $configuration, $dataSet, $language);
    }

    /**
     * @Route("/attributes", methods={"POST"})
     *
     * @IsGranted("ATTRIBUTE_CREATE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/attribute")
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAttribute(Request $request): Response
    {
        try {
            $model = new CreateAttributeFormModel();
            $form = $this->createForm(AttributeCreateForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CreateAttributeFormModel $data */
                $data = $form->getData();

                $command = new CreateAttributeCommand(
                    $data->type,
                    $data->code,
                    new TranslatableString($data->label),
                    new TranslatableString($data->hint),
                    new TranslatableString($data->placeholder),
                    $data->multilingual,
                    $data->groups,
                    (array) $data->parameters,
                    $data->options->getValues()
                );
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId()->getValue());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/attributes/{attribute}", methods={"GET"}, requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("ATTRIBUTE_READ")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param AbstractAttribute $attribute
     *
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @return Response
     */
    public function getAttribute(AbstractAttribute $attribute): Response
    {
        $result = $this->attributeQuery->getAttribute($attribute->getId());

        if ($result) {
            return new SuccessResponse($result);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/attributes/{attribute}", methods={"PUT"}, requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("ATTRIBUTE_UPDATE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/attribute")
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param AbstractAttribute $attribute
     * @param Request           $request
     *
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @return Response
     */
    public function updateAttribute(AbstractAttribute $attribute, Request $request): Response
    {
        try {
            $model = new UpdateAttributeFormModel(new AttributeType($attribute->getType()));
            $form = $this->createForm(AttributeUpdateForm::class, $model, ['method' => 'PUT']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UpdateAttributeFormModel $data */
                $data = $form->getData();

                $command = new UpdateAttributeCommand(
                    $attribute->getId(),
                    new TranslatableString($data->label),
                    new TranslatableString($data->hint),
                    new TranslatableString($data->placeholder),
                    $data->groups,
                    (array) $data->parameters,
                    $data->options->getValues()
                );
                $this->messageBus->dispatch($command);

                return new SuccessResponse(['id' => $command->getId()]);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        return $this->createRestResponse($form);
    }

    /**
     * @Route("/attributes/{attribute}", methods={"DELETE"}, requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("ATTRIBUTE_DELETE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request - Invalid attribute id",
     *)
     * @SWG\Response(
     *     response=204,
     *     description="No content - Successful removing attribute",
     *)
     * @SWG\Response(
     *     response=405,
     *     description="Method Not Allowed - Attribute can't be deleted",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found - Attribute not exists",
     * )
     *
     * @param AbstractAttribute $attribute
     *
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @return Response
     */
    public function deleteAttribute(AbstractAttribute $attribute): Response
    {
        $templates = $this->attributeTemplateQuery->getDesignTemplatesByAttributeId($attribute->getId());
        if (empty($templates)) {
            $command = new DeleteAttributeCommand($attribute->getId());
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        return new MethodNotAllowedResponse([
            'message' => 'Attribute used in templates',
            'templates' => $templates,
        ]);
    }
}
