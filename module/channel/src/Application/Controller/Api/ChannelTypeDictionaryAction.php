<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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
    private ChannelTypeDictionaryProvider $provider;

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
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns export profile dictionary",
     * )
     */
    public function __invoke(Language $language): Response
    {
        $dictionary = $this->provider->provide($language);

        return new SuccessResponse($dictionary);
    }
}
