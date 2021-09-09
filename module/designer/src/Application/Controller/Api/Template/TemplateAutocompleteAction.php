<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Controller\Api\Template;

use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("templates/autocomplete", methods={"GET"})
 */
class TemplateAutocompleteAction
{
    private TemplateQueryInterface $templateQuery;

    public function __construct(TemplateQueryInterface $templateQuery)
    {
        $this->templateQuery = $templateQuery;
    }

    /**
     * @SWG\Tag(name="Designer")
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
     *     description="Return templates",
     * )
     */
    public function __invoke(Request $request): array
    {
        $search = $request->query->get('search');
        $limit = $request->query->has('limit') ? $request->query->getInt('limit') : null;
        $field = $request->query->get('field');
        $order = $request->query->get('order');

        return $this->templateQuery->autocomplete($search, $limit, $field, $order);
    }
}
