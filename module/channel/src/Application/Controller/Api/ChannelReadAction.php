<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route(
 *     name="ergonode_channel_read",
 *     path="/channels/{channel}",
 *     methods={"GET"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ChannelReadAction
{
    private ChannelFormFactoryProvider $provider;

    private NormalizerInterface $normalizer;

    public function __construct(ChannelFormFactoryProvider $provider, NormalizerInterface $normalizer)
    {
        $this->provider = $provider;
        $this->normalizer = $normalizer;
    }

    /**
     * @IsGranted("CHANNEL_GET")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="channel",
     *     in="path",
     *     type="string",
     *     description="Channel id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns channel",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     */
    public function __invoke(AbstractChannel $channel): Response
    {
        $form = $this->provider->provide($channel->getType())->create($channel);
        $result = $this->normalizer->normalize($form);
        $result['type'] = $channel->getType();
        $result['id'] = $channel->getId();

        return new SuccessResponse($result);
    }
}
