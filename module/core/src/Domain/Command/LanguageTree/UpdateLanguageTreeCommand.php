<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Command\LanguageTree;

use Ergonode\Core\Application\Model\LanguageTree\LanguageTreeNodeFormModel;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use JMS\Serializer\Annotation as JMS;

class UpdateLanguageTreeCommand implements DomainCommandInterface
{
    /**
     * @var LanguageNode
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\LanguageNode")
     */
    private LanguageNode $languages;

    /**
     * @param LanguageTreeNodeFormModel $language
     */
    public function __construct(LanguageTreeNodeFormModel $language)
    {
        $this->languages = $this->createNode($language);
    }

    /**
     * @return LanguageNode
     */
    public function getLanguages(): LanguageNode
    {
        return $this->languages;
    }

    /**
     * @param LanguageTreeNodeFormModel $languages
     *
     * @return LanguageNode
     */
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
