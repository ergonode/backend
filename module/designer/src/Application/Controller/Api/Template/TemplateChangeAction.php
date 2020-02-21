<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Controller\Api\Template;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Designer\Application\Form\TemplateForm;
use Ergonode\Designer\Application\Model\Form\TemplateFormModel;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Infrastructure\Factory\TemplateCommandFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_designer_template_change",
 *     path="/templates/{template}",
 *     methods={"PUT"},
 *     requirements={"template" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class TemplateChangeAction
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var TemplateCommandFactory
     */
    private TemplateCommandFactory $commandFactory;

    /**
     * @param MessageBusInterface    $messageBus
     * @param FormFactoryInterface   $formFactory
     * @param TemplateCommandFactory $commandFactory
     */
    public function __construct(
        MessageBusInterface $messageBus,
        FormFactoryInterface $formFactory,
        TemplateCommandFactory $commandFactory
    ) {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @IsGranted("TEMPLATE_DESIGNER_UPDATE")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="template",
     *     in="path",
     *     type="string",
     *     description="Template id"
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
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Update template"
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
    public function __invoke(Template $template, Request $request): Response
    {
        $model = new TemplateFormModel();
        $form = $this->formFactory->create(TemplateForm::class, $model, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TemplateFormModel $data */
            $command = $this->commandFactory->getUpdateTemplateCommand($template->getId(), $form->getData());
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }
}
