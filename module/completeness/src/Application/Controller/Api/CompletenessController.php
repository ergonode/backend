<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Application\Controller\Api;

use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Editor\Domain\Provider\DraftProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 */
class CompletenessController extends AbstractApiController
{
    /**
     * @var CompletenessCalculator
     */
    private $calculator;

    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var DraftProvider
     */
    private $provider;

    /**
     * @param CompletenessCalculator      $calculator
     * @param TemplateRepositoryInterface $repository
     * @param DraftProvider               $provider
     */
    public function __construct(CompletenessCalculator $calculator, TemplateRepositoryInterface $repository, DraftProvider $provider)
    {
        $this->calculator = $calculator;
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * @Route(
     *     "/products/{product}/draft/completeness", methods={"GET"}, requirements = {"product" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
     * )
     *
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
     *     default="EN",
     *     description="Language Code",
     * )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Get draft grid",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @throws \Exception
     */
    public function getCompleteness(AbstractProduct $product, Language $language): Response
    {
        $draft = $this->provider->provide($product);
        $template = $this->repository->load($product->getTemplateId());
        Assert::notNull($template, sprintf('Can\'t find template %s', $product->getTemplateId()->getValue()));

        $result = $this->calculator->calculate($draft, $template, $language);

        return $this->createRestResponse($result);
    }
}
