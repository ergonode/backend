<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper;

use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\GetCategoryList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @deprecated
 */
class CategoryTreeMapper
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopwer6CategoryRepositoryInterface
     */
    private Shopwer6CategoryRepositoryInterface $categoryShopware;

    /**
     * @param TreeRepositoryInterface             $treeRepository
     * @param CategoryRepositoryInterface         $categoryRepository
     * @param Shopware6Connector                  $connector
     * @param Shopwer6CategoryRepositoryInterface $categoryShopware
     */
    public function __construct(
        TreeRepositoryInterface $treeRepository,
        CategoryRepositoryInterface $categoryRepository,
        Shopware6Connector $connector,
        Shopwer6CategoryRepositoryInterface $categoryShopware
    ) {
        $this->treeRepository = $treeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->connector = $connector;
        $this->categoryShopware = $categoryShopware;
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param Uuid                      $treeId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function map(Shopware6ExportApiProfile $exportProfile, Uuid $treeId)
    {
        $tree = $this->treeRepository->load($treeId);
        Assert::notNull($tree, sprintf('Tree %s not exists', $treeId));

        $category = [];
        foreach ($tree->getCategories() as $node) {
            $this->singleNode($node, $category);
        }
        $shopwareCategory = $this->getCategoryList($exportProfile);

        $this->save($exportProfile, $shopwareCategory, $category);
    }

    /**
     * @param Node  $node
     * @param array $result
     */
    private function singleNode(
        Node $node,
        array &$result
    ) {
        $category = $this->categoryRepository->load(Uuid::fromString($node->getCategoryId()->getValue()));


        $result[] = $category;
        foreach ($node->getChildren() as $node2) {
            $this->singleNode($node2, $result);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param array                     $shopwareCategory
     * @param ExportCategory            $category
     *
     * @return string|null
     */
    private function findCategory(
        Shopware6ExportApiProfile $exportProfile,
        array $shopwareCategory,
        ExportCategory $category
    ) {
        $name = $category->getName()->get($exportProfile->getDefaultLanguage());
        $name = $name ? $name : $category->getCode();

        foreach ($shopwareCategory as $item) {
            if ($item['name'] === $name) {
                return (string) $item['id'];
            }
        }

        return null;
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param array                     $shopwareCategoryList
     * @param array                     $categoryList
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function save(Shopware6ExportApiProfile $exportProfile, array $shopwareCategoryList, array $categoryList)
    {
        foreach ($categoryList as $category) {
            $shopwareId = $this->findCategory($exportProfile, $shopwareCategoryList, $category);
            if ($shopwareId) {
                $this->categoryShopware->save(
                    $exportProfile->getId(),
                    CategoryId::createFromUuid($category->getId()),
                    $shopwareId
                );
            }
        }
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     *
     * @return object|string|null
     */
    private function getCategoryList(Shopware6ExportApiProfile $exportProfile)
    {
        $action = new GetCategoryList();

        return $this->connector->execute($exportProfile, $action);
    }
}
