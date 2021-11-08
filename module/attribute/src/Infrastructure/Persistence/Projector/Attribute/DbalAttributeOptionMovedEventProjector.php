<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionMovedEvent;

class DbalAttributeOptionMovedEventProjector extends AbstractDbalAttributeOptionEventProjector
{
    public function __invoke(AttributeOptionMovedEvent $event): void
    {
        try {
            $from = $this->getPosition($event->getAggregateId(), $event->getOptionId());
            $to = $event->getIndex();

            var_dump('from '.$from);
            var_dump('to '.$to);

            $this->print($event);

            if ($from > $to) {
                $this->connection->delete(
                    self::TABLE,
                    [
                        'attribute_id' => $event->getAggregateId()->getValue(),
                        'option_id' => $event->getOptionId()->getValue(),
                        'index' => $from,
                    ]
                );

                $this->mergePosition($event->getAggregateId(), $from);
                $this->shiftPosition($event->getAggregateId(), $to);

                $this->connection->insert(
                    self::TABLE,
                    [
                        'attribute_id' => $event->getAggregateId()->getValue(),
                        'option_id' => $event->getOptionId()->getValue(),
                        'index' => $to,
                    ]
                );
            } else {
                var_dump($to);
                $this->shiftPosition($event->getAggregateId(), $to);

                $this->print($event);

                $this->connection->delete(
                    self::TABLE,
                    [
                        'attribute_id' => $event->getAggregateId()->getValue(),
                        'option_id' => $event->getOptionId()->getValue(),
                        'index' => $from,
                    ]
                );

                $this->connection->insert(
                    self::TABLE,
                    [
                        'attribute_id' => $event->getAggregateId()->getValue(),
                        'option_id' => $event->getOptionId()->getValue(),
                        'index' => $to,
                    ]
                );



                $this->mergePosition($event->getAggregateId(), $from);

                //  $this->print($event);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());





            die('wewefwe');
        }

      //throw new \Exception('brake');
    }

    private function print(AttributeOptionMovedEvent $event): void
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('index, option_id')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('attribute_id', ':id'))
            ->setParameter(':id', $event->getAggregateId()->getValue())
            ->orderBy('index')
            ->execute()
            ->fetchAllAssociative();

        foreach ($result as $line) {
            echo $line['index'].' : '.$line['option_id'].PHP_EOL;
        }

        var_dump('------------------------');
    }
}
