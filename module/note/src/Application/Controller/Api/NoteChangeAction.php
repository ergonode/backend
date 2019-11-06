<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Note\Application\Form\Model\CreateNoteFormModel;
use Ergonode\Note\Application\Form\Model\UpdateNoteFormModel;
use Ergonode\Note\Application\Form\UpdateNoteForm;
use Ergonode\Note\Domain\Command\UpdateNoteCommand;
use Ergonode\Note\Domain\Entity\Note;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_note_change",
 *     path="/notes/{note}",
 *     methods={"PUT"},
 *     requirements={"note"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class NoteChangeAction
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param MessageBusInterface  $messageBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(MessageBusInterface $messageBus, FormFactoryInterface $formFactory)
    {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @SWG\Tag(name="Note")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     *
     * @SWG\Parameter(
     *     name="note",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Note Id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Note body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/note")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Update category",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Note\Domain\Entity\Note")
     *
     * @param Note    $note
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Note $note, Request $request): Response
    {
        try {
            $model = new UpdateNoteFormModel();
            $form = $this->formFactory->create(UpdateNoteForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CreateNoteFormModel $data */
                $data = $form->getData();
                $command = new UpdateNoteCommand(
                    $note->getId(),
                    $data->content
                );
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
