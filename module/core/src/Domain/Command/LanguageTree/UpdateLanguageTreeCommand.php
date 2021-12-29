<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Command\LanguageTree;

use Ergonode\Core\Application\Model\LanguageTree\LanguageTreeNodeFormModel;
use Ergonode\Core\Domain\Command\CoreCommandInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;

class UpdateLanguageTreeCommand implements CoreCommandInterface
{
    private LanguageNode $languages;

    public function __construct(LanguageTreeNodeFormModel $language)
    {
        $this->languages = $this->createNode($language);
    }

    public function getLanguages(): LanguageNode
    {
        return $this->languages;
    }

    private function createNode(LanguageTreeNodeFormModel $languages): LanguageNode
    {
        $node = new LanguageNode(new LanguageId($languages->languageId));

        foreach ($languages->children as $child) {
            $child = $this->createNode($child);
            $node->addChild($child);
        }

        return $node;
    }
}
