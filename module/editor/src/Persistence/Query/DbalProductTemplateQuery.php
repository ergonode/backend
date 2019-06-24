<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Editor\Domain\Query\ProductTemplateQueryInterface;

/**
 * Class DbalProductTemplateQuery
 */
class DbalProductTemplateQuery implements ProductTemplateQueryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductDraftId $productDraftId
     * @param Language       $language
     *
     * @return array
     */
    public function getTemplateView(ProductDraftId $productDraftId, Language $language): array
    {
        $template = $this->getTemplate($productDraftId);
        $templateId = new TemplateId($template['id']);
        $template['elements'] = $this->getTemplateElements($productDraftId, $templateId, $language);

        return $template;
    }

    /**
     * @param ProductDraftId $draftId
     *
     * @return array
     */
    private function getTemplate(ProductDraftId $draftId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb
            ->select('t.id, t.name, d.id AS draft_id')
            ->from('designer.template', 't')
            ->join('t', 'designer.product', 'p', 'p.template_id = t.id')
            ->join('p', 'designer.draft', 'd', 'd.product_id = p.product_id')
            ->where($qb->expr()->eq('d.id', ':draftId'))
            ->setParameter(':draftId', $draftId->getValue())
            ->execute()
            ->fetch();

        return $result;
    }

    /**
     * @param ProductDraftId $draftId
     * @param TemplateId     $templateId
     * @param Language       $language
     *
     * @return array
     */
    private function getTemplateElements(ProductDraftId $draftId, TemplateId $templateId, Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb
            ->select('te.element_id AS id, te.x, te.y, te.width, te.height, te.required, e.variant, e.type, COALESCE(lt.value, e.code) AS name, ht.value AS hint, pt.value as placeholder, e.parameters, dv.value')
            ->from('designer.template_element', 'te')
            ->innerJoin('te', 'designer.element', 'e', 'te.element_id = e.id')
            ->leftJoin('e', 'designer.element_label', 'lt', 'lt.element_id = e.id AND lt.language = :language')
            ->leftJoin('e', 'designer.element_hint', 'ht', 'ht.element_id = e.id AND ht.language = :language')
            ->leftJoin('e', 'designer.element_placeholder', 'pt', 'pt.element_id = e.id AND pt.language = :language')
            ->leftJoin('te', 'designer.draft_value', 'dv', 'dv.element_id = te.element_id AND dv.draft_id = :draftId AND (dv.language = :language OR dv.language IS NULL)')
            ->where($qb->expr()->eq('te.template_id', ':templateId'))
            ->setParameter(':templateId', $templateId->getValue())
            ->setParameter(':draftId', $draftId->getValue())
            ->setParameter(':language', $language->getCode())
            ->addOrderBy('te.x', 'ASC')
            ->addOrderBy('te.y', 'ASC')
            ->execute()
            ->fetchAll();

        foreach ($records as $key => $record) {
            $options = $this->getElementOptions($record['id'], $language);
            if (!empty($options)) {
                $records[$key]['options'] = $options;
            }
        }

        return array_map(
            function ($item) {
                if (!empty($item['parameters'])) {
                    $item['parameters'] = \json_decode($item['parameters'], true);
                } else {
                    unset($item['parameters']);
                }

                return $item;
            },
            $records
        );
    }

    /**
     * @param string   $id
     * @param Language $language
     *
     * @return array
     */
    private function getElementOptions(string $id, Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb
            ->select('DISTINCT ao.key, vt.value')
            ->from('attribute_option', 'ao')
            ->leftJoin('ao', 'value_translation', 'vt', 'ao.value_id = vt.value_id AND (vt.language = :language OR vt.language IS NULL)')
            ->andWhere($qb->expr()->eq('ao.attribute_id', ':elementId'))
            ->setParameter(':elementId', $id)
            ->setParameter(':language', $language->getCode())
            ->execute()
            ->fetchAll();

        return $records;
    }
}
