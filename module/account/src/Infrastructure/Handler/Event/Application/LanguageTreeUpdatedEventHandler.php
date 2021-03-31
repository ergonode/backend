<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\Event\Application;

use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Core\Application\Event\LanguageTreeUpdatedEvent;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class LanguageTreeUpdatedEventHandler
{
    private AccountQueryInterface $accountQuery;

    private UserRepositoryInterface $userRepository;

    private LanguageQueryInterface $languageQuery;

    public function __construct(
        AccountQueryInterface $accountQuery,
        UserRepositoryInterface $userRepository,
        LanguageQueryInterface $languageQuery
    ) {
        $this->accountQuery = $accountQuery;
        $this->userRepository = $userRepository;
        $this->languageQuery = $languageQuery;
    }

    public function __invoke(LanguageTreeUpdatedEvent $event): void
    {
        $languageIds = $this->createArray($event->getTree()->getLanguages());
        $languages = $this->getLanguages($languageIds);
        $users = $this->accountQuery->getUsers();

        foreach ($users as $user) {
            $delete = $this->getDeleteLanguage($user['language_privileges_collection'], $languages);
            if ($delete) {
                $this->userDeleteLanguage(new UserId($user['id']), $delete);
            }
        }
    }

    private function userDeleteLanguage(UserId $userId, array $languages): void
    {
        $user = $this->userRepository->load($userId);
        if ($user) {
            $collection = $user->getLanguagePrivilegesCollection();
            foreach ($languages as $language) {
                unset($collection[$language]);
            }
            $user->changeLanguagePrivilegesCollection($collection);
            $this->userRepository->save($user);
        }
    }

    /**
     * @param array    $userLanguages
     * @param string[] $language
     *
     * @return string[]
     */
    private function getDeleteLanguage(array $userLanguages, array $language): array
    {
        foreach ($language as $item) {
            unset($userLanguages[$item]);
        }

        return array_keys($userLanguages);
    }

    /**
     * @param LanguageId[] $languages
     *
     * @return string[]
     */
    private function getLanguages(array $languages): array
    {
        $result = [];
        foreach ($languages as $languageId) {
            $code = $this->languageQuery->getLanguageById($languageId->getValue());
            if ($code) {
                $result[] = $code->getCode();
            }
        }

        return $result;
    }

    /**
     * @return LanguageId[]
     */
    private function createArray(LanguageNode $languages): array
    {
        $children = [$languages->getLanguageId()];

        foreach ($languages->getChildren() as $child) {
            $children = array_merge($children, $this->createArray($child));
        }

        return $children;
    }
}