<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Webmozart\Assert\Assert;

/**
 * @Route(
 *     name="ergonode_product_read_inherited_values_by_langauge",
 *     path="products/{product}/inherited/{productLanguage}",
 *     methods={"GET"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductReadInheritedValuesByLanguageAction
{
    private TranslationInheritanceCalculator $calculator;

    private AttributeRepositoryInterface $attributeRepository;

    private AttributeQueryInterface $attributeQuery;

    public function __construct(
        TranslationInheritanceCalculator $calculator,
        AttributeRepositoryInterface $attributeRepository,
        AttributeQueryInterface $attributeQuery
    ) {
        $this->calculator = $calculator;
        $this->attributeRepository = $attributeRepository;
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_PRODUCT_GET_INHERITED")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Get product",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="productLanguage",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Product Langage",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return product ",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(AbstractProduct $product, Language $productLanguage): array
    {
        $result = [
            'id' => $product->getId()->getValue(),
        ];
        foreach ($product->getAttributes() as $code => $value) {
            $attributeId = $this->attributeQuery->findAttributeIdByCode(new AttributeCode($code));
            Assert::notNull($attributeId);
            $attribute = $this->attributeRepository->load($attributeId);
            if ($attribute) {
                $scope = $attribute->getScope();
                $result['attributes'][$code] = $this->calculator->calculate($scope, $value, $productLanguage);
            }
        }

        return $result;
    }
}
