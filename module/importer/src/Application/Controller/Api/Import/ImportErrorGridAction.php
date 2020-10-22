<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\Importer\Infrastructure\Grid\ImportErrorsGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_import_read_error_grid",
 *     path="/sources/{source}/imports/{import}/errors",
 *     methods={"GET"},
 *     requirements={
 *          "source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *          "import" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class ImportErrorGridAction
{
    private ImportErrorsGrid $grid;

    private ImportQueryInterface $query;

    private GridRenderer $gridRenderer;

    public function __construct(ImportErrorsGrid $grid, ImportQueryInterface $query, GridRenderer $gridRenderer)
    {
        $this->grid = $grid;
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("IMPORT_READ")
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
     *     description="Returns import  errors",
     * )
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource")
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Import")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        Language $language,
        AbstractSource $source,
        Import $import,
        RequestGridConfiguration $configuration
    ): Response {
        $dataSet = $this->query->getErrorDataSet($import->getId(), $language);

        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
