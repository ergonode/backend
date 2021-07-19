<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("categories/autocomplete", methods={"GET"})
 */
class CategoryAutocompleteAction
{
    private CategoryQueryInterface $categoryQuery;

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
     */
    public function __invoke(Language $language, Request $request): array
    {
        $search = $request->query->get('search');
        $limit = $request->query->has('limit') ? $request->query->getInt('limit') : null;
        $field = $request->query->get('field');
        $order = $request->query->get('order');

        return $this->categoryQuery->autocomplete($language, $search, $limit, $field, $order);
    }
}
