<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Infrastructure\Grid\TemplateGroupGrid;
use Ergonode\Grid\RequestGridConfiguration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 */
class TemplateGroupController extends AbstractApiController
{
    /**
     * @var TemplateGroupQueryInterface
     */
    private $query;

    /**
     * @var TemplateGroupGrid
     */
    private $grid;

    /**
     * @param TemplateGroupQueryInterface $query
     * @param TemplateGroupGrid           $grid
     */
    public function __construct(TemplateGroupQueryInterface $query, TemplateGroupGrid $grid)
    {
        $this->query = $query;
        $this->grid = $grid;
    }

    /**
     * @Route("/templates/groups", methods={"GET"})
     *
     * @SWG\Tag(name="Designer")
     *  * @SWG\Parameter(
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of designer template groups",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getGroups(Language $language, Request $request): Response
    {
        $dataSet = $this->query->getDataSet();
        $configuration = new RequestGridConfiguration($request);

        $result = $this->renderGrid($this->grid, $configuration, $dataSet, $language);

        return $this->createRestResponse($result);
    }
}
