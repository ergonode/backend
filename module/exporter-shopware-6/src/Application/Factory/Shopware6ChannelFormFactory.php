<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;
use Ergonode\ExporterShopware6\Application\Form\Shopware6ChannelForm;

class Shopware6ChannelFormFactory implements ChannelFormFactoryInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supported(string $type): bool
    {
        return Shopware6Channel::TYPE === $type;
    }

    public function create(AbstractChannel $channel = null): FormInterface
    {
        $model = new Shopware6ChannelFormModel($channel);
        if ($channel) {
            return $this->formFactory->create(
                Shopware6ChannelForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
        }

        return $this->formFactory->create(Shopware6ChannelForm::class, $model);
    }
}
