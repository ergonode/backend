<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronize;

use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\GetCategoryList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PatchCategoryUpdate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PostCategoryCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Persistence\Repository\DbalShopwer6CategoryRepository;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
class CategorySynchronize
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var DbalShopwer6CategoryRepository
     */
    private DbalShopwer6CategoryRepository $categoryShopware;

    /**
     * @param Shopware6Connector             $connector
     * @param TreeRepositoryInterface        $treeRepository
     * @param CategoryRepositoryInterface    $categoryRepository
     * @param DbalShopwer6CategoryRepository $categoryShopware
     */
    public function __construct(
        Shopware6Connector $connector,
        TreeRepositoryInterface $treeRepository,
        CategoryRepositoryInterface $categoryRepository,
        DbalShopwer6CategoryRepository $categoryShopware
    ) {
        $this->connector = $connector;
        $this->treeRepository = $treeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->categoryShopware = $categoryShopware;
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param Uuid                      $treeId
     */
    public function synchronize(Shopware6ExportApiProfile $exportProfile, Uuid $treeId)
    {
        $tree = $this->treeRepository->load($treeId);
        Assert::notNull($tree, sprintf('Tree %s not exists', $treeId));

        foreach ($tree->getCategories() as $node) {
            $this->buildStep($exportProfile, $node);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param Node                      $node
     * @param string|null               $parentId
     */
    private function buildStep(Shopware6ExportApiProfile $exportProfile, Node $node, string $parentId = null)
    {
        $categoryId = Uuid::fromString($node->getCategoryId()->getValue());
        $newParent = null;
        $exportProfileId = Uuid::fromString($exportProfile->getId()->getValue());
        if ($this->categoryShopware->exists($exportProfileId, $categoryId)) {
            $shopware6Category = $this->categoryShopware->load($exportProfileId, $categoryId);
            $this->update($exportProfile, $shopware6Category, $parentId);
            $newParent = $shopware6Category->getId();
        } else {
            $category = $this->categoryRepository->load($categoryId);
            $newParent = $this->create($exportProfile, $category, $parentId);
        }

        foreach ($node->getChildrens() as $child) {
            $this->buildStep($exportProfile, $child, $newParent);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param ExportCategory            $category
     * @param string|null               $parentId
     *
     * @return string|null
     */
    private function create(Shopware6ExportApiProfile $exportProfile, ExportCategory $category, string $parentId = null)
    {
        $name = $category->getName()->get(Language::fromString('en'));
        $name = $name ? $name : $category->getCode();

        $action = new PostCategoryCreate($name, $parentId);
        $this->connector->execute($exportProfile, $action);

//todo save to db new ID
        return $this->getCategory($exportProfile, $name);
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param Shopware6Category         $category
     * @param string|null               $parentId
     *
     * @return object|string|null
     */
    private function update(
        Shopware6ExportApiProfile $exportProfile,
        Shopware6Category $category,
        string $parentId = null
    ) {
        $name = $category->getCategory()->getName()->get(Language::fromString('en'));
        $name = $name ?: $category->getCategory()->getCode();
        $action = new PatchCategoryUpdate($category->getId(), $name, $parentId);

        return $this->connector->execute($exportProfile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     * @param string                    $name
     *
     * @return string|null
     */
    private function getCategory(Shopware6ExportApiProfile $exportProfile, string $name): ?string
    {
        $list = $this->getCategoryList($exportProfile);

        foreach ($list as $item) {
            if ($item['name'] === $name) {
                return (string) $item['id'];
            }
        }

        return null;
    }

    /**
     * @param Shopware6ExportApiProfile $exportProfile
     *
     * @return array|object|string|null
     */
    private function getCategoryList(Shopware6ExportApiProfile $exportProfile)
    {
        $action = new GetCategoryList();

        return $this->connector->execute($exportProfile, $action);
    }
}
