<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Infrastructure\Grid\ImportErrorsGridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Importer\Domain\Query\ImportErrorGridQueryInterface;

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
    private ImportErrorsGridBuilder $gridBuilder;

    private ImportErrorGridQueryInterface $query;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        ImportErrorsGridBuilder $gridBuilder,
        ImportErrorGridQueryInterface $query,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->gridBuilder = $gridBuilder;
        $this->query = $query;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("IMPORT_GET_GRID_ERROR")
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
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->query->getGridQuery($import->getId(), $language));

        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
