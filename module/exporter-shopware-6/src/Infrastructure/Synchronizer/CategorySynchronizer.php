<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CategoryQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\GetCategoryList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PatchCategoryUpdate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PostCategoryCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class CategorySynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6CategoryQueryInterface
     */
    private Shopware6CategoryQueryInterface $categoryShopwareQuery;

    /**
     * @var Shopware6CategoryRepositoryInterface
     */
    private Shopware6CategoryRepositoryInterface $categoryShopwareRepository;

    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @param Shopware6Connector                   $connector
     * @param Shopware6CategoryQueryInterface      $categoryShopwareQuery
     * @param Shopware6CategoryRepositoryInterface $categoryShopwareRepository
     * @param TreeRepositoryInterface              $treeRepository
     * @param CategoryRepositoryInterface          $categoryRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6CategoryQueryInterface $categoryShopwareQuery,
        Shopware6CategoryRepositoryInterface $categoryShopwareRepository,
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
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->synchronizeTree($channel);
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeTree(Shopware6Channel $channel): void
    {
        $treeId = $channel->getCategoryTree();
        if ($treeId) {
            $tree = $this->treeRepository->load(Uuid::fromString($treeId->getValue()));
            Assert::notNull($tree, sprintf('Tree %s not exists', $treeId));

            foreach ($tree->getCategories() as $node) {
                $this->buildStep($channel, $node);
            }
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param Node             $node
     * @param string|null      $parentId
     */
    private function buildStep(Shopware6Channel $channel, Node $node, string $parentId = null): void
    {
        $newParent = null;
        if ($this->categoryShopwareRepository->exists($channel->getId(), $node->getCategoryId())) {
            $newParent = $this->update($channel, $node->getCategoryId(), $parentId);
        } else {
            $newParent = $this->create($channel, $node->getCategoryId(), $parentId);
            $this->categoryShopwareRepository->save($channel->getId(), $node->getCategoryId(), $newParent);
        }

        foreach ($node->getChildren() as $child) {
            $this->buildStep($channel, $child, $newParent);
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param CategoryId       $categoryId
     * @param string|null      $parentId
     *
     * @return string
     */
    private function create(
        Shopware6Channel $channel,
        CategoryId $categoryId,
        ?string $parentId = null
    ): string {
        $category = $this->categoryRepository->load(Uuid::fromString($categoryId));

        $name = $category->getName()->get($channel->getDefaultLanguage());
        $name = $name ? $name : $category->getCode();

        $action = new PostCategoryCreate($name, $parentId);
        $this->connector->execute($channel, $action);

        $shopwareCategory = $this->getShopwareCategory($channel, $name);

        return $shopwareCategory['id'];
    }

    /**
     * @param Shopware6Channel $channel
     * @param CategoryId       $categoryId
     * @param string|null      $parentId
     *
     * @return string
     */
    private function update(
        Shopware6Channel $channel,
        CategoryId $categoryId,
        ?string $parentId = null
    ): string {
        $categoryShopware = $this->categoryShopwareRepository->load($channel->getId(), $categoryId);

        $name = $categoryShopware->getCategory()->getName()->get($channel->getDefaultLanguage());
        $name = $name ? $name : $categoryShopware->getCategory()->getCode();

        $action = new PatchCategoryUpdate($categoryShopware->getId(), $name, $parentId);
        $this->connector->execute($channel, $action);

        return $categoryShopware->getId();
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $categoryList = $this->getShopwareCategoryList($channel);
        foreach ($categoryList as $category) {
            $categoryShopware = $this->categoryShopwareQuery->loadByShopwareId($channel->getId(), $category['id']);
            if ($categoryShopware) {
                $this->categoryShopwareRepository->save(
                    $channel->getId(),
                    CategoryId::createFromUuid($categoryShopware->getCategory()->getId()),
                    $categoryShopware->getId()
                );
            }
        }
        $this->categoryShopwareQuery->cleanData($channel->getId(), $start);
    }

    /**
     * @param Shopware6Channel $channel
     * @param string           $name
     *
     * @return array|null
     */
    private function getShopwareCategory(Shopware6Channel $channel, string $name): ?array
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

        $categoryList = $this->connector->execute($channel, $action);
        if (is_array($categoryList) && count($categoryList) > 0) {
            return reset($categoryList);
        }

        return null;
    }


    /**
     * @param Shopware6Channel $channel
     *
     * @return array
     */
    private function getShopwareCategoryList(Shopware6Channel $channel): array
    {
        $action = new GetCategoryList();

        return $this->connector->execute($channel, $action);
    }
}
