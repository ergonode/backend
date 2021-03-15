<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ramsey\Uuid\Uuid;

final class Version20180719132703 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS designer');

        $this->addSql('
            CREATE TABLE designer.template_group (
                id UUID NOT NULL,
                name VARCHAR(32) NOT NULL,
                custom boolean DEFAULT FALSE,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE designer.template (
                id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                image_id UUID DEFAULT NULL,
                default_label UUID DEFAULT NULL,
                default_image UUID DEFAULT NULL,
                template_group_id UUID NOT NULL,                                   
                PRIMARY KEY(id)
            )
        ');
        $this->addSql(
            'ALTER TABLE designer.template 
                    ADD CONSTRAINT template_template_group_id_fk 
                        FOREIGN KEY (template_group_id) REFERENCES designer.template_group (id) ON DELETE CASCADE'
        );

        $this->addSql('
            CREATE TABLE designer.template_element (
                template_id UUID NOT NULL,
                x INTEGER NOT NULL,
                y INTEGER NOT NULL,
                width INTEGER NOT NULL,
                height INTEGER NOT NULL,   
                properties JSONB NOT NULL,             
                PRIMARY KEY(template_id, x, y)
            )
        ');
        $this->addSql('
            ALTER TABLE designer.template_element
                ADD CONSTRAINT template_element_template_id_fk 
                    FOREIGN KEY (template_id) REFERENCES designer.template (id) ON DELETE CASCADE');

        $this->addSql('
            CREATE TABLE designer.element_type (
                type VARCHAR(32) NOT NULL,            
                variant VARCHAR(32) NOT NULL,        
                label VARCHAR(32) NOT NULL,
                min_width INTEGER NOT NULL,
                min_height INTEGER NOT NULL,
                max_width INTEGER NOT NULL,
                max_height INTEGER NOT NULL,
                PRIMARY KEY(type)
            )
        ');

        $this->addSql('
            CREATE TABLE designer.product (
                product_id UUID NOT NULL,
                template_id UUID NOT NULL,                               
                PRIMARY KEY(product_id, template_id)
            )
        ');
        $this->addSql('
            ALTER TABLE designer.product 
                ADD CONSTRAINT product_template_id_fk
                    FOREIGN KEY (template_id) REFERENCES designer.template (id) ON DELETE CASCADE');

        $this->addType('TEXT', 'attribute', 'Text');
        $this->addType('NUMERIC', 'attribute', 'Numeric');
        $this->addType('TEXT_AREA', 'attribute', 'Textarea', 1, 1, 4, 10);
        $this->addType('DATE', 'attribute', 'Date');
        $this->addType('SELECT', 'attribute', 'Select');
        $this->addType('MULTI_SELECT', 'attribute', 'Multi Select');
        $this->addType('IMAGE', 'attribute', 'Image', 1, 1, 4, 10);
        $this->addType('PRICE', 'attribute', 'Price');
        $this->addType('UNIT', 'attribute', 'Unit');
        $this->addType('GALLERY', 'attribute', 'Gallery', 1, 1, 4, 10);
        $this->addType('FILE', 'attribute', 'File', 1, 1, 4, 10);
        $this->addType('SECTION', 'ui', 'Section');

        $this->addGroup('418c48d3-d2c3-4c30-b627-93850c38d59c', 'Suggested');
        $this->addGroup('641c614f-0732-461f-892f-b6df97939599', 'My templates', true);

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Template designer']);
        $this->createTemplateDesignerPrivileges(
            [
                'TEMPLATE_DESIGNER_CREATE' => 'Template designer',
                'TEMPLATE_DESIGNER_READ' => 'Template designer',
                'TEMPLATE_DESIGNER_UPDATE' => 'Template designer',
                'TEMPLATE_DESIGNER_DELETE' => 'Template designer',
            ]
        );

        $this->createEventStoreEvents([
            'Ergonode\Designer\Domain\Event\TemplateCreatedEvent' => 'Template created',
            'Ergonode\Designer\Domain\Event\TemplateElementAddedEvent' => 'Template element added to template',
            'Ergonode\Designer\Domain\Event\TemplateElementChangedEvent' => 'Template element changed',
            'Ergonode\Designer\Domain\Event\TemplateElementRemovedEvent' => 'Template element removed',
            'Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent' => 'Template group removed',
            'Ergonode\Designer\Domain\Event\TemplateImageAddedEvent' => 'Template image added',
            'Ergonode\Designer\Domain\Event\TemplateImageChangedEvent' => 'Template image changed',
            'Ergonode\Designer\Domain\Event\TemplateImageRemovedEvent' => 'Template image removed',
            'Ergonode\Designer\Domain\Event\TemplateDefaultLabelAddedEvent' => 'Template default label added',
            'Ergonode\Designer\Domain\Event\TemplateDefaultLabelChangedEvent' => 'Template default label  changed',
            'Ergonode\Designer\Domain\Event\TemplateDefaultLabelRemovedEvent' => 'Template default label removed',
            'Ergonode\Designer\Domain\Event\TemplateDefaultImageAddedEvent' => 'Template default Image added',
            'Ergonode\Designer\Domain\Event\TemplateDefaultImageChangedEvent' => 'Template default Image  changed',
            'Ergonode\Designer\Domain\Event\TemplateDefaultImageRemovedEvent' => 'Template default Image removed',
            'Ergonode\Designer\Domain\Event\TemplateNameChangedEvent' => 'Template name changed',
            'Ergonode\Designer\Domain\Event\TemplateRemovedEvent' => 'Template removed',
        ]);
    }

    private function addGroup(string $uuid, string $name, bool $custom = false): TemplateGroupId
    {
        $id = new TemplateGroupId($uuid);
        $this->addSql(
            'INSERT INTO designer.template_group (id, name, custom) VALUES (?, ?, ?)',
            [$id, $name, (int) $custom]
        );

        return $id;
    }

    private function addType(
        string $code,
        string $variant,
        string $label,
        int $minWidth = 1,
        int $minHeight = 1,
        int $maxWidth = 4,
        int $maxHeight = 1
    ): void {
        $this
            ->addSql(
                \sprintf(
                    'INSERT INTO designer.element_type 
                                (type, variant, label, min_width, min_height, max_width, max_height) 
                                VALUES (\'%s\',  \'%s\', \'%s\', %d, %d, %d, %d)',
                    $code,
                    $variant,
                    $label,
                    $minWidth,
                    $minHeight,
                    $maxWidth,
                    $maxHeight
                )
            );
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createTemplateDesignerPrivileges(array $collection): void
    {
        foreach ($collection as $code => $area) {
            $this->addSql(
                'INSERT INTO privileges (id, code, area) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $code,  $area, ]
            );
        }
    }
}
