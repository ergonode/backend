<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
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
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Infrastructure\Grid\AttributeGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
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
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @var ExistingRelationshipMessageBuilderInterface
     */
    private $existingRelationshipMessageBuilder;

    /**
     * @param AttributeGrid                               $attributeGrid
     * @param AttributeQueryInterface                     $attributeQuery
     * @param AttributeGridQueryInterface                 $attributeGridQuery
     * @param MessageBusInterface                         $messageBus
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
    public function __construct(
        AttributeGrid $attributeGrid,
        AttributeQueryInterface $attributeQuery,
        AttributeGridQueryInterface $attributeGridQuery,
        MessageBusInterface $messageBus,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->attributeGrid = $attributeGrid;
        $this->attributeQuery = $attributeQuery;
        $this->attributeGridQuery = $attributeGridQuery;
        $this->messageBus = $messageBus;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipMessageBuilder = $existingRelationshipMessageBuilder;
    }

    /**
     * @Route("/attributes", methods={"GET"})
     *
     * @IsGranted("ATTRIBUTE_READ")
     *
     * @SWG\Tag(name="Attribute")
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
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getAttributes(Language $language, RequestGridConfiguration $configuration): Response
    {
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
     *     response=201,
     *     description="Returns attribute ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
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

                return new CreatedResponse($command->getId());
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
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @param AbstractAttribute $attribute
     * @param Request           $request
     *
     * @return Response
     */
    public function updateAttribute(AbstractAttribute $attribute, Request $request): Response
    {
        try {
            $model = new UpdateAttributeFormModel(new AttributeType($attribute->getType()));
            $form = $this->createForm(AttributeUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
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

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
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
     *     description="Attribute id"
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Successful removing attribute"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Attribute not exists"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Existing relationships"
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
        $relationships = $this->relationshipsResolver->resolve($attribute->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteAttributeCommand($attribute->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
