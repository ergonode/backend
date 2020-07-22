<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Application\Provider\ChannelTypeDictionaryProvider;

/**
 * @Route(
 *     path="/dictionary/channels",
 *     methods={"GET"},
 * )
 */
class ChannelTypeDictionaryAction
{
    /**
     * @var ChannelTypeDictionaryProvider
     */
    private ChannelTypeDictionaryProvider $provider;

    /**
     * @param ChannelTypeDictionaryProvider $provider
     */
    public function __construct(ChannelTypeDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns export profile dictionary",
     * )
     * @param Language $language
     *
     * @return Response
     */
    public function __invoke(Language $language)
    {
        $dictionary = $this->provider->provide($language);

        return new SuccessResponse($dictionary);
    }
}
