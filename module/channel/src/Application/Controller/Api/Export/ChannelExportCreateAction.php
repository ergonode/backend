<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api\Export;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Channel\Domain\Command\StartChannelExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 * @Route(
 *     name="ergonode_channel_export_create",
 *     path="/channels/{channel}/exports",
 *     methods={"POST"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ChannelExportCreateAction
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
     *     name="channel",
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
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     *
     * @param AbstractChannel $channel
     * @param Request         $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractChannel $channel, Request $request): Response
    {
        $command = new StartChannelExportCommand(
            ExportId::generate(),
            $channel->getId(),
        );

        $this->commandBus->dispatch($command);

        return new CreatedResponse($command->getExportId());
    }
}
