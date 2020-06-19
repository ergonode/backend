<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronize;

use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Query\Shopwer6CategoryQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\GetCategoryList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PatchCategoryUpdate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PostCategoryCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
class CategorySynchronize implements SynchronizeInterface
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopwer6CategoryQueryInterface
     */
    private Shopwer6CategoryQueryInterface $categoryShopwareQuery;

    /**
     * @var Shopwer6CategoryRepositoryInterface
     */
    private Shopwer6CategoryRepositoryInterface $categoryShopwareRepository;

    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @param Shopware6Connector                  $connector
     * @param Shopwer6CategoryQueryInterface      $categoryShopwareQuery
     * @param Shopwer6CategoryRepositoryInterface $categoryShopwareRepository
     * @param TreeRepositoryInterface             $treeRepository
     * @param CategoryRepositoryInterface         $categoryRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopwer6CategoryQueryInterface $categoryShopwareQuery,
        Shopwer6CategoryRepositoryInterface $categoryShopwareRepository,
        TreeRepositoryInterface $treeRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->connector = $connector;
        $this->categoryShopwareQuery = $categoryShopwareQuery;
        $this->categoryShopwareRepository = $categoryShopwareRepository;
        $this->treeRepository = $treeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param ExportId                  $id
     * @param Shopware6ExportApiProfile $profile
     */
    public function synchronize(ExportId $id, Shopware6ExportApiProfile $profile): void
    {
        $this->synchronizeShopware($profile);
        $this->synchronizeTree($profile);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeTree(Shopware6ExportApiProfile $profile): void
    {
        $treeId = $profile->getCategoryTree();
        if ($treeId) {
            $tree = $this->treeRepository->load(Uuid::fromString($treeId->getValue()));
            Assert::notNull($tree, sprintf('Tree %s not exists', $treeId));

            foreach ($tree->getCategories() as $node) {
                $this->buildStep($profile, $node);
            }
        }
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param Node                      $node
     * @param string|null               $parentId
     */
    private function buildStep(Shopware6ExportApiProfile $profile, Node $node, string $parentId = null): void
    {
        $newParent = null;
        if ($this->categoryShopwareRepository->exists($profile->getId(), $node->getCategoryId())) {
            $newParent = $this->update($profile, $node->getCategoryId(), $parentId);
        } else {
            $newParent = $this->create($profile, $node->getCategoryId(), $parentId);
            $this->categoryShopwareRepository->save($profile->getId(), $node->getCategoryId(), $newParent);
        }

        foreach ($node->getChildren() as $child) {
            $this->buildStep($profile, $child, $newParent);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param CategoryId                $categoryId
     * @param string|null               $parentId
     *
     * @return string
     */
    private function create(
        Shopware6ExportApiProfile $profile,
        CategoryId $categoryId,
        ?string $parentId = null
    ): string {
        $category = $this->categoryRepository->load(Uuid::fromString($categoryId));

        $name = $category->getName()->get($profile->getDefaultLanguage());
        $name = $name ? $name : $category->getCode();

        $action = new PostCategoryCreate($name, $parentId);
        $this->connector->execute($profile, $action);

        $shopwareCategory = $this->getShopwareCategory($profile, $name);

        return $shopwareCategory['id'];
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param CategoryId                $categoryId
     * @param string|null               $parentId
     *
     * @return string
     */
    private function update(
        Shopware6ExportApiProfile $profile,
        CategoryId $categoryId,
        ?string $parentId = null
    ): string {
        $categoryShopware = $this->categoryShopwareRepository->load($profile->getId(), $categoryId);

        $name = $categoryShopware->getCategory()->getName()->get($profile->getDefaultLanguage());
        $name = $name ? $name : $categoryShopware->getCategory()->getCode();

        $action = new PatchCategoryUpdate($categoryShopware->getId(), $name, $parentId);
        $this->connector->execute($profile, $action);

        return $categoryShopware->getId();
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeShopware(Shopware6ExportApiProfile $profile): void
    {
        $start = new \DateTimeImmutable();
        $categoryList = $this->getShopwareCategoryList($profile);
        foreach ($categoryList as $category) {
            $categoryShopware = $this->categoryShopwareQuery->loadByShopwareId($profile->getId(), $category['id']);
            if ($categoryShopware) {
                $this->categoryShopwareRepository->save(
                    $profile->getId(),
                    CategoryId::createFromUuid($categoryShopware->getCategory()->getId()),
                    $categoryShopware->getId()
                );
            }
        }
        $this->categoryShopwareQuery->clearBefore($profile->getId(), $start);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param string                    $name
     *
     * @return array|null
     */
    private function getShopwareCategory(Shopware6ExportApiProfile $profile, string $name): ?array
    {
        $query = [
            [
                'query' => [
                    'type' => 'equals',
                    'field' => 'name',
                    'value' => $name,
                ],
            ],
        ];
        $action = new GetCategoryList($query);

        $categoryList = $this->connector->execute($profile, $action);
        if (is_array($categoryList) && count($categoryList) > 0) {
            return reset($categoryList);
        }

        return null;
    }


    /**
     * @param Shopware6ExportApiProfile $profile
     *
     * @return array
     */
    private function getShopwareCategoryList(Shopware6ExportApiProfile $profile): array
    {
        $action = new GetCategoryList();

        return $this->connector->execute($profile, $action);
    }
}
