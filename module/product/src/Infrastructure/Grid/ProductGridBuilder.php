<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Core\Application\Security\Security;
use Ergonode\Core\Domain\User\UserInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Ergonode\Grid\Request\RequestColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Webmozart\Assert\Assert;
use Ergonode\Grid\Column\LinkColumn;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\AttributeColumnProvider;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Grid\Column\IdColumn;

class ProductGridBuilder implements GridBuilderInterface
{
    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $repository;

    private AttributeColumnProvider $provider;

    private LanguageQueryInterface $languageQuery;

    private Security $security;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $repository,
        AttributeColumnProvider $provider,
        LanguageQueryInterface $languageQuery,
        Security $security,
        UserRepositoryInterface $userRepository
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->repository = $repository;
        $this->provider = $provider;
        $this->languageQuery = $languageQuery;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new ProductGrid();

        $codes = $this->attributeQuery->getAllAttributeCodes();

        $user = $this->security->getUser();
        if (!$user instanceof UserInterface) {
            throw new AuthenticationException();
        }
        //todo refactor in feature
        if (!$user instanceof User) {
            $userId = $user->getId();
            $user = $this->userRepository->load($userId);
            Assert::isInstanceOf($user, User::class, sprintf('User not found %s', $userId));
        }
        $result = [];

        /** @var RequestColumn[] $columns */
        $columns = array_merge(
            [
                new RequestColumn('id'),
                new RequestColumn('index'),
                new RequestColumn('sku'),
            ],
            $configuration->getColumns()
        );

        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('index', new IntegerColumn('index', 'Index', new TextFilter()))
            ->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter()));

        foreach ($columns as $column) {
            if (!array_key_exists($column->getKey(), $result)) {
                $code = $column->getColumn();
                $key = $column->getKey();
                $language = $column->getLanguage() ?: $language;

                if (in_array($code, $codes, true)
                    && $user->hasReadLanguagePrivilege($language)
                    && $this->languageQuery->getLanguageNodeInfo($language)) {
                    $id = AttributeId::fromKey((new AttributeCode($code))->getValue());
                    $attribute = $this->repository->load($id);
                    Assert::notNull($attribute, sprintf('Can\'t find attribute with code "%s"', $code));

                    $new = $this->provider->provide($attribute, $language);
                    $new->setAttribute($attribute);
                    $new->setExtension('element_id', $id->getValue());
                    $new->setExtension('parameters', $attribute->getParameters());
                    $new->setEditable($attribute->isEditable());
                    $new->setDeletable($attribute->isDeletable());
                    if (!$column->isShow()) {
                        $new->setVisible(false);
                    }

                    if ($column->getLanguage()) {
                        $new->setLanguage($column->getLanguage());
                    }
                    if (!$user->hasEditLanguagePrivilege($language)) {
                        $new->setEditable(false);
                    }
                    if ($attribute->getScope()->isGlobal()) {
                        $rootLanguage = $this->languageQuery->getRootLanguage();
                        if (!$rootLanguage->isEqual($language)) {
                            $new->setEditable(false);
                        }
                    }
                    $grid->addColumn($key, $new);
                }
            }
        }

        $grid->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_read',
                'privilege' => 'PRODUCT_GET',
                'parameters' => ['language' => $language->getCode(), 'product' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_product_change',
                'privilege' => 'PRODUCT_PUT',
                'parameters' => ['language' => $language->getCode(), 'product' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_product_delete',
                'privilege' => 'PRODUCT_DELETE',
                'parameters' => ['language' => $language->getCode(), 'product' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]))->orderBy('index', 'DESC');

        return $grid;
    }
}
