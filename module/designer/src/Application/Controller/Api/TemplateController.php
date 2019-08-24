<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Application\Form\TemplateForm;
use Ergonode\Designer\Application\Model\Form\TemplateFormModel;
use Ergonode\Designer\Domain\Checker\TemplateRelationChecker;
use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Infrastructure\Factory\TemplateCommandFactory;
use Ergonode\Designer\Infrastructure\Grid\TemplateGrid;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class TemplateController extends AbstractController
{
    /**
     * @var TemplateQueryInterface
     */
    private $designerTemplateQuery;

    /**
     * @var TemplateGrid
     */
    private $templateGrid;

    /**
     * @var TemplateRelationChecker
     */
    private $templateChecker;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var TemplateCommandFactory
     */
    private $createCommandFactory;

    /**
     * @param TemplateQueryInterface  $designerTemplateQuery
     * @param TemplateGrid            $templateGrid
     * @param TemplateRelationChecker $templateChecker
     * @param MessageBusInterface     $messageBus
     * @param TemplateCommandFactory  $createCommandFactory
     */
    public function __construct(
        TemplateQueryInterface $designerTemplateQuery,
        TemplateGrid $templateGrid,
        TemplateRelationChecker $templateChecker,
        MessageBusInterface $messageBus,
        TemplateCommandFactory $createCommandFactory
    ) {
        $this->designerTemplateQuery = $designerTemplateQuery;
        $this->templateGrid = $templateGrid;
        $this->templateChecker = $templateChecker;
        $this->messageBus = $messageBus;
        $this->createCommandFactory = $createCommandFactory;
    }

    /**
     * @Route("/templates", methods={"GET"})
     *
     * @IsGranted("TEMPLATE_DESIGNER_READ")
     *
     * @SWG\Tag(name="Designer")
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
     *     response=200,
     *     description="Returns templates",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\GridConfigurationInterface")
     *
     * @param Language                   $language
     * @param GridConfigurationInterface $configuration
     *
     * @return Response
     */
    public function getTemplates(Language $language, GridConfigurationInterface $configuration): Response
    {
        $dataSet = $this->designerTemplateQuery->getDataSet();

        return new GridResponse($this->templateGrid, $configuration, $dataSet, $language);
    }

    /**
     * @Route("/templates", methods={"POST"})
     *
     * @IsGranted("TEMPLATE_DESIGNER_CREATE")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add template",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/template")
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
     *     description="Create template",
     * )
     * @SWG\Response(
     *     response="400",
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
    public function createTemplate(Request $request): Response
    {
        $model = new TemplateFormModel();
        $form = $this->createForm(TemplateForm::class, $model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TemplateFormModel $data */
            $command = $this->createCommandFactory->getCreateTemplateCommand($form->getData());
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/templates/{template}", methods={"PUT"}, requirements={"template" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("TEMPLATE_DESIGNER_UPDATE")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="template",
     *     in="path",
     *     type="string",
     *     description="Template id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add template",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/template")
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
     *     response=204,
     *     description="Update template",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @param Template $template
     * @param Request  $request
     *
     * @ParamConverter(class="Ergonode\Designer\Domain\Entity\Template")
     *
     * @return Response
     */
    public function updateTemplate(Template $template, Request $request): Response
    {
        $model = new TemplateFormModel();
        $form = $this->createForm(TemplateForm::class, $model, ['method' => Request::METHOD_PUT]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TemplateFormModel $data */
            $command = $this->createCommandFactory->getUpdateTemplateCommand($template->getId(), $form->getData());
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/templates/{template}", methods={"GET"}, requirements={"template" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("TEMPLATE_DESIGNER_READ")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="template",
     *     in="path",
     *     type="string",
     *     description="Template id",
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
     *     response=200,
     *     description="Returns template",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Designer\Domain\Entity\Template")
     *
     * @param Template $template
     *
     * @return Response
     */
    public function getTemplate(Template $template): Response
    {
        return new SuccessResponse($template);
    }

    /**
     * @Route("/templates/{template}", methods={"DELETE"}, requirements={"templates" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("TEMPLATE_DESIGNER_DELETE")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="template",
     *     in="path",
     *     type="string",
     *     description="Template id",
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
     *     response=204,
     *     description="Returns template",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Can't remove Template, it has relations to products"
     * )
     *
     * @param Template $template
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Designer\Domain\Entity\Template")
     */
    public function deleteTemplate(Template $template): Response
    {
        if ($this->templateChecker->hasRelations($template)) {
            throw new ConflictHttpException('Can\'t remove Template, it has relations to products');
        }

        $command = new DeleteTemplateCommand($template->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
