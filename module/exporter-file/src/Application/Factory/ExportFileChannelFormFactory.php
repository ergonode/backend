<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Application\Form\ExporterFileConfigurationForm;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

class ExportFileChannelFormFactory implements ChannelFormFactoryInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supported(string $type): bool
    {
        return FileExportChannel::TYPE === $type;
    }

    /**
     * @param AbstractChannel|FileExportChannel|null $channel
     */
    public function create(AbstractChannel $channel = null): FormInterface
    {
        Assert::nullOrIsInstanceOf($channel, FileExportChannel::class);
        $model = new ExporterFileConfigurationModel($channel);
        $method = $channel ? Request::METHOD_PUT : Request::METHOD_POST;

        return $this->formFactory->create(ExporterFileConfigurationForm::class, $model, ['method' => $method]);
    }
}
