<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Domain\Provider\PrivilegeDictionaryProvider;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class PrivilegeController extends AbstractController
{
    /**
     * @var PrivilegeDictionaryProvider
     */
    private $provider;

    /**
     * @param PrivilegeDictionaryProvider $provider
     */
    public function __construct(PrivilegeDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @Route("/dictionary/privileges", methods={"GET"})
     *
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns privilege collection",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Language $language
     *
     * @return Response
     */
    public function getUsers(Language $language): Response
    {
        $result = $this->provider->provide($language);

        return new SuccessResponse($result);
    }
}
