<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChannelTypeDictionaryProvider
{
    /**
     * @var ChannelTypeProvider
     */
    private ChannelTypeProvider $provider;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param ChannelTypeProvider $provider
     * @param TranslatorInterface $translator
     */
    public function __construct(ChannelTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        foreach ($this->provider->provide() as $type) {
            $result[$type] = $this->translator->trans($type, [], 'channel', $language->getCode());
        }

        asort($result);

        return $result;
    }
}
