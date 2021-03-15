<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Dictionary;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/attributes/groups", methods={"GET"})
 */
class AttributeGroupReadAction
{
    private AttributeGroupQueryInterface $attributeGroupQuery;

    public function __construct(AttributeGroupQueryInterface $attributeGroupQuery)
    {
        $this->attributeGroupQuery = $attributeGroupQuery;
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
     *     description="Returns collection attribute groups"
     * )
     */
    public function __invoke(Language $language): Response
    {
        $types = $this->attributeGroupQuery->getAttributeGroups($language);

        return new SuccessResponse($types);
    }
}
