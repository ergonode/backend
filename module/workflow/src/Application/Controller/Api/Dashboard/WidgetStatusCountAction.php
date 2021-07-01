<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Dashboard;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_dashboard_widget_status_count",
 *     path="dashboard/widget/{workflowLanguage}/status-count",
 *     methods={"GET"}
 * )
 */
class WidgetStatusCountAction
{
    private StatusQueryInterface $query;

    public function __construct(StatusQueryInterface $query)
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
     *     description="widget status count information",
     * )
     */
    public function __invoke(Language $language, Language $workflowLanguage): array
    {
        return $this->query->getStatusCount($language, $workflowLanguage);
    }
}
