<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Domain\Query\LanguageTreeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LanguageTreeProvider implements LanguageTreeProviderInterface
{
    /**
     * @var LanguageTreeQueryInterface
     */
    private LanguageTreeQueryInterface $query;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param LanguageTreeQueryInterface $query
     * @param TokenStorageInterface      $tokenStorage
     * @param TranslatorInterface        $translator
     */
    public function __construct(
        LanguageTreeQueryInterface $query,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator
    ) {
        $this->query = $query;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getActiveLanguages(Language $language): array
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $privileges = $user->getLanguagePrivilegesCollection();
            $tree = $this->query->getTree();

            return $this->map($language, $tree, $privileges);
        }

        return [];
    }

    /**
     * @param Language             $language
     * @param array                $treeLanguages
     * @param LanguagePrivileges[] $privileges
     *
     * @return array
     */
    private function map(Language $language, array $treeLanguages, array $privileges): array
    {
        $result = [];
        $defaultPrivilege = new LanguagePrivileges(false, false);
        foreach ($treeLanguages as $treeLanguage) {
            $code = $treeLanguage['code'];
            $result[$code] = array_merge(
                $treeLanguage,
                [
                    'name' => $this->translator->trans($code, [], 'language', $language->getCode()),
                    'privileges' => isset($privileges[$code]) ? $privileges[$code] : $defaultPrivilege,
                ]
            );
        }

        return $result;
    }
}
