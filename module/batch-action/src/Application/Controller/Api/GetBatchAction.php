<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Domain\Query\BatchActionQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 * @Route(
 *     name="ergonode_batch_action_read",
 *     path="/batch-action/{action}",
 *     methods={"GET"},
 *     requirements={"action" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class GetBatchAction
{
    private BatchActionQueryInterface $query;

    public function __construct(BatchActionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Batch action")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="path",
     *     type="string",
     *     description="Batch action id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns batch information",
     * )
     *
     * @ParamConverter(class="Ergonode\BatchAction\Domain\Entity\BatchAction")
     */
    public function __invoke(Language $language, BatchAction $action): Response
    {
        $result = $this->query->getInformation($action->getId(), $language);

        return new SuccessResponse($result);
    }
}
