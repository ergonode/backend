<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Infrastructure\Grid\BatchActionEntryGridBuilder;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\BatchAction\Domain\Query\BatchActionEntryGridQueryInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 * @Route(
 *     name="ergonode_batch_action_entry_grid_read",
 *     path="/batch-action/{action}/entries",
 *     methods={"GET"},
 *     requirements={"action" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class GetBatchActionEntryGridAction
{
    private BatchActionEntryGridQueryInterface $query;

    private BatchActionEntryGridBuilder $gridBuilder;

    private DbalDataSetFactory $dataSetFactory;

    private GridRenderer $gridRenderer;

    public function __construct(
        BatchActionEntryGridQueryInterface $query,
        BatchActionEntryGridBuilder $gridBuilder,
        DbalDataSetFactory $dataSetFactory,
        GridRenderer $gridRenderer
    ) {
        $this->query = $query;
        $this->gridBuilder = $gridBuilder;
        $this->dataSetFactory = $dataSetFactory;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @SWG\Tag(name="Batch action")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="path",
     *     type="string",
     *     description="Batch action id",
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
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns batch information",
     * )
     *
     * @ParamConverter(class="Ergonode\BatchAction\Domain\Entity\BatchAction")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(
        Language $language,
        BatchAction $action,
        RequestGridConfiguration $configuration
    ): Response {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->dataSetFactory->create($this->query->getGridQuery($action->getId()));
        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
