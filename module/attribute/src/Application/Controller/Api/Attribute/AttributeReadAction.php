<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_attribute_read",
 *     path="/attributes/{attribute}",
 *     methods={"GET"},
 *     requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AttributeReadAction
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @IsGranted("ATTRIBUTE_GET")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     *  @SWG\Parameter(
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
    public function __invoke(AbstractAttribute $attribute): Response
    {
        $result = $this->attributeQuery->getAttribute($attribute->getId());

        if ($result) {
            return new SuccessResponse($result);
        }

        throw new NotFoundHttpException();
    }
}
