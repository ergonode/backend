<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Importer\Domain\Entity\Import;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 * @Route(
 *     name="ergonode_import_read",
 *     path="/sources/{source}/imports/{import}",
 *     methods={"GET"},
 *     requirements={
 *          "source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *          "import" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class ImportShowAction
{
    private ImportQueryInterface $query;

    public function __construct(ImportQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("IMPORT_GET_GRID")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="source",
     *     in="path",
     *     type="string",
     *     description="Source Id",
     * )
     * @SWG\Parameter(
     *     name="import",
     *     in="path",
     *     type="string",
     *     description="Import Id",
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
     *
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Import")
     */
    public function __invoke(Language $language, Import $import): Response
    {
        $result = $this->query->getInformation($import->getId(), $language);

        return new SuccessResponse($result);
    }
}
