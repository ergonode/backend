<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("attributes/autocomplete", methods={"GET"})
 */
class AttributeAutocompleteAction
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="filtered type"
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
     *     name="system",
     *     in="query",
     *     required=false,
     *     type="boolean",
     *     description="True - only system attributes, False - only not system attributes",
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
    public function __invoke(Language $language, Request $request): Response
    {
        $search = $request->query->get('search');
        $type = $request->query->get('type');
        $limit = $request->query->getInt('limit', null);
        $field = $request->query->get('field');
        $system = $request->query->get('system');
        $order = $request->query->get('order');

        $data = $this->attributeQuery->autocomplete($language, $search, $type, $limit, $field, $system, $order);

        return new SuccessResponse($data);
    }
}
