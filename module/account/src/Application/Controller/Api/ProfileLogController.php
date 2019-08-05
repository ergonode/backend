<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Domain\Query\LogQueryInterface;
use Ergonode\Account\Infrastructure\Grid\LogGrid;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ProfileLogController extends AbstractApiController
{
    /**
     * @var LogQueryInterface
     */
    private $query;

    /**
     * @var LogGrid
     */
    private $grid;

    /**
     * @param LogQueryInterface $query
     * @param LogGrid           $grid
     */
    public function __construct(LogQueryInterface $query, LogGrid $grid)
    {
        $this->query = $query;
        $this->grid = $grid;
    }

    /**
     * @Route("/profile/log", methods={"GET"})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @SWG\Tag(name="Profile")
     *
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
     *     enum={"recorded_at", "author", "author_id", "event"},
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
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns User Log collection",
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getLog(Request $request): Response
    {
        $user = $this->getUser();
        $configuration = new RequestGridConfiguration($request);

        $result = $this->renderGrid($this->grid, $configuration, $this->query->getDataSet($user->getId()), $user->getLanguage());

        return $this->createRestResponse($result);
    }
}
