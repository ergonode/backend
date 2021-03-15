<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\AttributeGroup;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_attribute_group_read",
 *     path="/attributes/groups/{attributeGroup}",
 *     methods={"GET"},
 *     requirements={"attributeGroup" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AttributeGroupReadAction
{
    /**
     * @IsGranted("ATTRIBUTE_GET_GROUP")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attributeGroup",
     *     in="path",
     *     type="string",
     *     description="Attribute Group id",
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
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     */
    public function __invoke(AttributeGroup $attributeGroup): Response
    {
        return new SuccessResponse($attributeGroup);
    }
}
