<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Multimedia;

use Ergonode\ExporterFile\Infrastructure\Builder\ExportMultimediaBuilderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Symfony\Component\Routing\RouterInterface;

class ExportMultimediaUrlBuilder implements ExportMultimediaBuilderInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function header(): array
    {
        return ['_url'];
    }

    public function build(AbstractMultimedia $multimedia, ExportLineData $line, Language $language): void
    {
        $url = $this->router->generate(
            'ergonode_multimedia_download',
            [
                'multimedia' => $multimedia->getId()->getValue(),
            ],
            RouterInterface::ABSOLUTE_URL,
        );

        $line->set('_url', $url);
    }
}
