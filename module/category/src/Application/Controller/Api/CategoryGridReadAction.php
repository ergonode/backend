<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Infrastructure\Grid\CategoryGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", methods={"GET"})
 */
class CategoryGridReadAction
{
    /**
     * @var CategoryGrid
     */
    private $categoryGrid;

    /**
     * @var CategoryQueryInterface
     */
    private $categoryQuery;

    /**
     * @param CategoryGrid           $categoryGrid
     * @param CategoryQueryInterface $categoryQuery
     */
    public function __construct(
        CategoryGrid $categoryGrid,
        CategoryQueryInterface $categoryQuery
    ) {
        $this->categoryGrid = $categoryGrid;
        $this->categoryQuery = $categoryQuery;
    }

    /**
     * @IsGranted("CATEGORY_READ")
     *
     * @SWG\Tag(name="Category")
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
     *     enum={"sku","name"},
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
     *     description="Returns import",
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
        $dataSet = $this->categoryQuery->getDataSet($language);

        return new GridResponse($this->categoryGrid, $configuration, $dataSet, $language);
    }
}
