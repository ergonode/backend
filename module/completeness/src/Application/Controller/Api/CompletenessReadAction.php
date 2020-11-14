<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Completeness\Domain\Query\CompletenessQueryInterface;

/**
 * @Route(
 *     "/products/{product}/draft/completeness",
 *     methods={"GET"},
 *     requirements = {"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class CompletenessReadAction
{
    private CompletenessQueryInterface $query;

    public function __construct(CompletenessQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Editor")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product id",
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
     *     description="Get draft grid",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(AbstractProduct $product, Language $language): Response
    {
        $result = $this->query->getCompleteness($product->getId(), $language);

        return new SuccessResponse($result);
    }
}
