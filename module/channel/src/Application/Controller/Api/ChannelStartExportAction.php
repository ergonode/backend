<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\Export\StartChannelExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_channel_start",
 *     path="/channels/{channel}/start",
 *     methods={"POST"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ChannelStartExportAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("CHANNEL_UPDATE")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Channel id",
     * )
     *    * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns Channel",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\Channel")
     *
     * @param Channel $channel
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Channel $channel, Request $request): Response
    {
        $command = new StartChannelExportCommand(
            ExportId::generate(),
            $channel->getId(),
            $channel->getExportProfileId()
        );

        $this->commandBus->dispatch($command);

        return new CreatedResponse($command->getExportId());
    }
}
