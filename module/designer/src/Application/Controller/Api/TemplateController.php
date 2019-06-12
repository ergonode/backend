<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Application\Form\TemplateForm;
use Ergonode\Designer\Application\Model\Form\TemplateFormModel;
use Ergonode\Designer\Domain\Checker\TemplateRelationChecker;
use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Infrastructure\Factory\TemplateCommandFactory;
use Ergonode\Designer\Infrastructure\Grid\TemplateGrid;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 */
class TemplateController extends AbstractApiController
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
     * @SWG\Tag(name="Designer")
     *
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns template",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getTemplates(Language $language, Request $request): Response
    {
        $dataSet = $this->designerTemplateQuery->getDataSet();
        $pagination = new RequestGridConfiguration($request);
        $collection = $this->templateGrid->render($dataSet, $pagination, $language);

        return $this->createRestResponse($collection);
    }

    /**
     * @Route("/templates", methods={"POST"})
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
     *     response=400,
     *     description="Form validation error",
     * )
     * @param Request $request
     *
     * @return Response
     */
    public function createTemplate(Request $request): Response
    {
        try {
            $model = new TemplateFormModel();
            $form = $this->createForm(TemplateForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TemplateFormModel $data */
                $command = $this->createCommandFactory->getCreateTemplateCommand($form->getData());
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (\Throwable $exception) {
            return $this->createRestResponse([$exception->getMessage(), explode(PHP_EOL, $exception->getTraceAsString())], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createRestResponse($form);
    }

    /**
     * @Route("/templates/{template}", methods={"PUT"}, requirements={"template" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
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
     *     response=200,
     *     description="Update template",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     * @param Template $template
     * @param Request  $request
     *
     * @ParamConverter(class="Ergonode\Designer\Domain\Entity\Template")
     *
     * @return Response
     */
    public function updateTemplate(Template $template, Request $request): Response
    {
        try {
            $model = new TemplateFormModel();
            $form = $this->createForm(TemplateForm::class, $model, ['method' => 'PUT']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                /** @var TemplateFormModel $data */
                $command = $this->createCommandFactory->getUpdateTemplateCommand($template->getId(), $form->getData());
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()]);
            }
        } catch (\Throwable $exception) {
            return $this->createRestResponse([$exception->getMessage(), $exception->getTraceAsString()], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createRestResponse($form);
    }

    /**
     * @Route("/templates/{template}", methods={"GET"}, requirements={"template" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
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
     * @param Language $language
     * @param string   $template
     *
     * @return Response
     */
    public function getTemplate(Language $language, string $template): Response
    {
        try {
            $templateId = new TemplateId($template);
            $result = $this->designerTemplateQuery->getTemplate($templateId, $language);
            if (!empty($result)) {
                return $this->createRestResponse($result);
            }

            return $this->createRestResponse(null, [], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            return $this->createRestResponse([$exception->getMessage(), explode(PHP_EOL, $exception->getTraceAsString())], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/templates/{template}", methods={"DELETE"}, requirements={"templates" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
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
     * @param Template $template
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Designer\Domain\Entity\Template")
     */
    public function deleteTemplate(Template $template): Response
    {
        if ($this->templateChecker->hasRelations($template)) {
            return $this->createRestResponse(['Can\'t remove Template, it has relations to products'], [], Response::HTTP_CONFLICT);
        }

        $command = new DeleteTemplateCommand($template->getId());
        $this->messageBus->dispatch($command);

        return $this->createRestResponse(null, [], Response::HTTP_ACCEPTED);
    }
}
