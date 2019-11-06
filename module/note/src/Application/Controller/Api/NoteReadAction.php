<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Note\Domain\Entity\Note;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_note_read",
 *     path="/notes/{note}",
 *     methods={"GET"},
 *     requirements={"note"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class NoteReadAction
{
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
     * @SWG\Parameter(
     *     name="note",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Note ID",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns note",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Note\Domain\Entity\Note")
     *
     * @param Note $note
     *
     * @return Response
     */
    public function __invoke(Note $note): Response
    {
        return new SuccessResponse($note);
    }
}
