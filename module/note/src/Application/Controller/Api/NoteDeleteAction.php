<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Application\Controller\Api;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Note\Domain\Command\DeleteNoteCommand;
use Ergonode\Note\Domain\Entity\Note;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_note_delete",
 *     path="/notes/{note}",
 *     methods={"DELETE"},
 *     requirements={"note"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class NoteDeleteAction
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @SWG\Tag(name="Note")
     * @SWG\Parameter(
     *     name="note",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Note ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Note\Domain\Entity\NOte")
     *
     * @param Note $note
     *
     * @return Response
     */
    public function __invoke(Note $note): Response
    {
        $command = new DeleteNoteCommand($note->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
