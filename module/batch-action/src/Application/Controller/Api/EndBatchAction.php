<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Swagger\Annotations as SWG;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\BatchAction\Domain\Command\EndBatchActionCommand;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

/**
 * @Route(
 *     name="ergonode_batch_action_end",
 *     path="/batch-action/{action}/end",
 *     methods={"PUT"},
 *     requirements={"action" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class EndBatchAction
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
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
     * @ParamConverter(class="Ergonode\BatchAction\Domain\Entity\BatchAction", name="action")
     */
    public function __invoke(BatchAction $action): void
    {
        if (!$action->getStatus()->isWaitingForDecision()) {
            throw new BadRequestHttpException(
                sprintf(
                    'Only Batch action in %s status Can be manually ended',
                    BatchActionStatus::WAITING_FOR_DECISION
                )
            );
        }

        $this->commandBus->dispatch(new EndBatchActionCommand($action->getId()));
    }
}
