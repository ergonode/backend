<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Exporter\Infrastructure\Grid\ExportProfileGrid;
use Ergonode\Exporter\Domain\Query\ExportProfileQueryInterface;

/**
 * @Route(
 *     path="/export-profile",
 *     methods={"GET"}
 * )
 */
class ExportProfileGridAction
{
    /**
     * @var ExportProfileGrid
     */
    private ExportProfileGrid $grid;

    /**
     * @var ExportProfileQueryInterface
     */
    private ExportProfileQueryInterface $query;

    /**
     * @var GridRenderer
     */
    private GridRenderer $renderer;

    /**
     * @param ExportProfileGrid           $grid
     * @param ExportProfileQueryInterface $query
     * @param GridRenderer                $renderer
     */
    public function __construct(ExportProfileGrid $grid, ExportProfileQueryInterface $query, GridRenderer $renderer)
    {
        $this->grid = $grid;
        $this->query = $query;
        $this->renderer = $renderer;
    }

    /**
     * @IsGranted("EXPORT_PROFILE_READ")
     *
     * @SWG\Tag(name="Export Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
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
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns export profile collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $dataSet = $this->query->getDataSet($language);

        $data = $this->renderer->render(
            $this->grid,
            $configuration,
            $dataSet,
            $language
        );

        return new SuccessResponse($data);
    }
}
