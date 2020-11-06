<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Query;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Model\BatchActionInformationModel;
use Ergonode\BatchAction\Domain\Query\BatchActionQueryInterface;
use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\Model\BatchActionEntryModel;

class DbalBatchActionQuery implements BatchActionQueryInterface
{
    private Connection $connection;

    private TranslatorInterface $translator;

    public function __construct(Connection $connection, TranslatorInterface $translator)
    {
        $this->connection = $connection;
        $this->translator = $translator;
    }

    public function getInformation(BatchActionId $id, Language $language): BatchActionInformationModel
    {
        $qb = $this->connection->createQueryBuilder();

        $record = $qb
            ->select('id, created_at, type')
            ->addSelect('(SELECT count(*) FROM batch_action_entry WHERE batch_action_id = id) AS all')
            ->addSelect('(SELECT count(*) FROM batch_action_entry WHERE batch_action_id = id 
            AND success IS NOT NULL) AS processed')
            ->addSelect('(SELECT max(processed_at) FROM batch_action_entry WHERE batch_action_id = id) 
            AS last_processed_at')
            ->from('batch_action')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();


        $model = new BatchActionInformationModel(
            new BatchActionId($record['id']),
            new BatchActionType($record['type']),
            $record['all'],
            $record['processed'],
            new \DateTime($record['created_at']),
            $record['all'] === $record['processed'] ? new \DateTime($record['last_processed_at']) : null
        );

        foreach ($this->getEntries($id, $language) as $entry) {
            $model->addEntry($entry);
        }

        return $model;
    }

    private function getEntries(BatchActionId $id, Language $language): array
    {
        $result = [];
        $qb = $this->connection->createQueryBuilder();
        $records = $qb
            ->select('fail_reason, resource_id')
            ->from('batch_action_entry')
            ->where($qb->expr()->eq('batch_action_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->andWhere($qb->expr()->eq('success', ':success'))
            ->setParameter(':success', false, \PDO::PARAM_BOOL)
            ->execute()
            ->fetchAll();

        foreach ($records as $record) {
            $entry = new BatchActionEntryModel(new AggregateId($record['resource_id']));
            $messages = json_decode($record['fail_reason'], true, 512, JSON_THROW_ON_ERROR);
            foreach ($messages as $message) {
                $entry->addMessage($this->translate($message, $language));
            }
            $result[] = $entry;
        }

        return $result;
    }

    private function translate(array $record, Language $language): string
    {
        return $this->translator->trans(
            $record['message'],
            $record['properties'],
            'batch-action',
            $language->getCode()
        );
    }
}
