<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Application\Controller\Api\Dashboard;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Completeness\Domain\Query\CompletenessQueryInterface;

/**
 * @Route(
 *     name="ergonode_dashboard_widget_completeness_count",
 *     path="dashboard/widget/completeness-count",
 *     methods={"GET"}
 * )
 */
class WidgetCompletenessCountAction
{
    private CompletenessQueryInterface $query;

    public function __construct(CompletenessQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Dashboard")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="widget completeness count information",
     * )
     */
    public function __invoke(Language $language): Response
    {
        $result = $this->query->getCompletenessCount($language);

        return new SuccessResponse($result);
    }
}
