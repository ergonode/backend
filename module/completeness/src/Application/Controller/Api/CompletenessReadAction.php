<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;

/**
 * @Route(
 *     "/products/{product}/completeness",
 *     methods={"GET"},
 *     requirements = {"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class CompletenessReadAction
{
    private CompletenessQueryInterface $query;

    private CompletenessCalculator $calculator;

    private TemplateRepositoryInterface $templateRepository;

    public function __construct(
        CompletenessQueryInterface $query,
        CompletenessCalculator $calculator,
        TemplateRepositoryInterface $templateRepository
    ) {
        $this->query = $query;
        $this->calculator = $calculator;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Product")
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
     *     description="Get product completenes information",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(AbstractProduct $product, Language $language): Response
    {
        $template = $this->templateRepository->load($product->getTemplateId());
        Assert::isInstanceOf($template, Template::class);
        $result = new CompletenessReadModel($language);
        foreach ($this->calculator->calculate($product, $template, $language) as $element) {
            $attributeId = $element->getAttributeId();
            $element = new CompletenessElementReadModel(
                $element->getAttributeId(),
                $this->query->getAttributeLabel($attributeId, $language),
                $element->isRequired(),
                $element->isFilled(),
            );

            $result->addCompletenessElement($element);
        }

        return new SuccessResponse($result);
    }
}
