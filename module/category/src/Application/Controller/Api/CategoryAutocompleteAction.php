<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("categories/autocomplete", methods={"GET"})
 */
class CategoryAutocompleteAction
{
    /**
     * @var CategoryQueryInterface
     */
    private CategoryQueryInterface $categoryQuery;

    /**
     * @param CategoryQueryInterface $categoryQuery
     */
    public function __construct(CategoryQueryInterface $categoryQuery)
    {
        $this->categoryQuery = $categoryQuery;
    }

    /**
     * @SWG\Tag(name="Category")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="searched value"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     required=false,
     *     type="integer",
     *     description="searched value count"
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
     * @SWG\Response(
     *     response=200,
     *     description="Return categories",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function __invoke(Language $language, Request $request): Response
    {
        $search = $request->query->get('search');
        $limit = $request->query->getInt('limit', null);
        $field = $request->query->get('field');
        $order = $request->query->get('order');

        $data = $this->categoryQuery->autocomplete($language, $search, $limit, $field, $order);

        return new SuccessResponse($data);
    }
}
