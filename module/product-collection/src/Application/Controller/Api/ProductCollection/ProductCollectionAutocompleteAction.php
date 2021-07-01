<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollection;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("collections/autocomplete", methods={"GET"})
 */
class ProductCollectionAutocompleteAction
{
    private ProductCollectionQueryInterface $productCollectionQuery;

    public function __construct(ProductCollectionQueryInterface $productCollectionQuery)
    {
        $this->productCollectionQuery = $productCollectionQuery;
    }

    /**
     * @SWG\Tag(name="Product Collection")
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
     *     description="Returns attributes",
     * )
     */
    public function __invoke(Language $language, Request $request): array
    {
        $search = $request->query->get('search');
        $limit = $request->query->getInt('limit', null);
        $field = $request->query->get('field');
        $order = $request->query->get('order');

        return $this->productCollectionQuery->autocomplete($language, $search, $limit, $field, $order);
    }
}
