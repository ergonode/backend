<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Dictionary;

use Ergonode\Account\Domain\Provider\PrivilegeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/privileges", methods={"GET"})
 */
class PrivilegeReadAction
{
    private PrivilegeDictionaryProvider $provider;

    public function __construct(PrivilegeDictionaryProvider $provider)
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
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns privilege collection"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language): array
    {
        return $this->provider->provide($language);
    }
}
