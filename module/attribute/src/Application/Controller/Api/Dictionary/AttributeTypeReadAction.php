<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/attributes/types", methods={"GET"})
 */
class AttributeTypeReadAction
{
    private AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider;

    public function __construct(AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider)
    {
        $this->attributeTypeDictionaryProvider = $attributeTypeDictionaryProvider;
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
     *     description="Returns collection attribute types"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language): Response
    {
        $types = $this->attributeTypeDictionaryProvider->getDictionary($language);

        return new SuccessResponse($types);
    }
}
