<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Relation;

use Ergonode\Multimedia\Infrastructure\Provider\MultimediaRelationInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;

class AttributeMultimediaRelation implements MultimediaRelationInterface
{
    private AttributeQueryInterface $query;

    private Security $security;

    private UrlGeneratorInterface $generator;

    private TranslatorInterface $translator;

    public function __construct(
        AttributeQueryInterface $query,
        Security $security,
        UrlGeneratorInterface $generator,
        TranslatorInterface $translator
    ) {
        $this->query = $query;
        $this->security = $security;
        $this->generator = $generator;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getRelation(MultimediaId $multimediaId, Language $language): array
    {
        $relations = $this->query->getMultimediaRelation($multimediaId);
        $result = [];
        foreach ($relations as $id => $name) {
            $attribute['name'] = $name;
            if ($this->security->isGranted('ATTRIBUTE_GET')) {
                $attribute['_link'] = [
                    'method' => Request::METHOD_GET,
                    'href' => $this->getUrl('ergonode_attribute_read', ['language' => $language, 'attribute' => $id]),
                ];
            }
            $result[] = $attribute;
        }

        return [
            'name' => $this->translator->trans('Attributes', [], 'attribute'),
            'type' => 'attribute',
            'relations' => $result,
        ];
    }

    /**
     * @param array $parameters
     */
    private function getUrl(string $name, array $parameters): string
    {
        return $this->generator->generate($name, $parameters, UrlGeneratorInterface::NETWORK_PATH);
    }
}
