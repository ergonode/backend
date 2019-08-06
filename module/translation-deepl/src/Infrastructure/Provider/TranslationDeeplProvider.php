<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;


use Ergonode\TranslationDeepl\Domain\Repository\TranslationDeeplRepositoryInterface;
use Scn\DeeplApiConnector\DeeplClient;
use Scn\DeeplApiConnector\Model\ResponseModelInterface;
use Scn\DeeplApiConnector\Model\TranslationConfig;

class TranslationDeeplProvider implements TranslationDeeplProviderInterface
{
    /**
     * @var DeeplClient
     */
    private $deeplClient;
    /**
     * @var string
     */
    private $deeplAuthKey;

    /**
     * TranslationDeeplProvider constructor.
     */
    public function __construct(DeeplClient $deeplClient, string $deeplAuthKey)
    {
        $this->deeplClient = $deeplClient;
        $this->deeplAuthKey = $deeplAuthKey;
    }

    public function provide(TranslationConfig $translation): ResponseModelInterface
    {
        $deepl = $this->deeplClient::create($this->deeplAuthKey);
        return $deepl->getTranslation($translation);
    }
}
