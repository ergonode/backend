<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Import\Application\Controller\Api\Import;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("imports/{import}", methods={"GET"}, requirements={"import"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
 */
class ImportShowAction
{
    /**
     * @IsGranted("IMPORT_READ")
     *
     * @SWG\Tag(name="Importer")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="import",
     *     in="path",
     *     type="string",
     *     description="Import id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param AbstractImport $import
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\AbstractImport")
     *
     * @return Response
     */
    public function __invoke(AbstractImport $import): Response
    {
        return new SuccessResponse($import);
    }
}
