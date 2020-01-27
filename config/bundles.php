<?php

return [
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => ['all' => true],
    Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle::class => ['dev' => true, 'test' => true],
    Ergonode\Migration\MigrationBundle::class => ['all' => true],
    Ergonode\Core\ErgonodeCoreBundle::class => ['all' => true],
    Ergonode\Notification\ErgonodeNotificationBundle::class => ['all' => true],
    Ergonode\Api\ErgonodeApiBundle::class => ['all' => true],
    Ergonode\EventSourcing\ErgonodeEventSourcingBundle::class => ['all' => true],
    Ergonode\Fixture\ErgonodeFixtureBundle::class => ['dev' => true, 'test' => true],
    Ergonode\Account\ErgonodeAccountBundle::class => ['all' => true],
    Ergonode\Workflow\ErgonodeWorkflowBundle::class => ['all' => true],
    Ergonode\Authentication\ErgonodeAuthenticationBundle::class => ['all' => true],
    Ergonode\Importer\ErgonodeImporterBundle::class => ['all' => true],
    Ergonode\Reader\ErgonodeReaderBundle::class => ['all' => true],
    Ergonode\Channel\ErgonodeChannelBundle::class => ['all' => true],
    Ergonode\Transformer\ErgonodeTransformerBundle::class => ['all' => true],
    Ergonode\Condition\ErgonodeConditionBundle::class => ['all' => true],
    Ergonode\Segment\ErgonodeSegmentBundle::class => ['all' => true],
    Ergonode\Category\ErgonodeCategoryBundle::class => ['all' => true],
    Ergonode\CategoryTree\ErgonodeCategoryTreeBundle::class => ['all' => true],
    Ergonode\Product\ErgonodeProductBundle::class => ['all' => true],
    Ergonode\ProductSimple\ErgonodeProductSimpleBundle::class => ['all' => true],
    Ergonode\Attribute\ErgonodeAttributeBundle::class => ['all' => true],
    Ergonode\Completeness\CompletenessBundle::class => ['all' => true],
    Ergonode\Designer\ErgonodeDesignerBundle::class => ['all' => true],
    Ergonode\Editor\ErgonodeEditorBundle::class => ['all' => true],
    Ergonode\Multimedia\ErgonodeMultimediaBundle::class => ['all' => true],
    Ergonode\Value\ErgonodeValueBundle::class => ['all' => true],
    Ergonode\TranslationDeepl\ErgonodeTranslationDeeplBundle::class => ['all' => true],
    Ergonode\Generator\ErgonodeGeneratorBundle::class => ['dev' => true],
    Ergonode\Grid\ErgonodeGridBundle::class => ['all' => true],
    Ergonode\Comment\ErgonodeCommentBundle::class => ['all' => true],
    Ergonode\Exporter\ErgonodeExporterBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Vich\UploaderBundle\VichUploaderBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Nelmio\CorsBundle\NelmioCorsBundle::class => ['all' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['dev' => true, 'test' => true],
];
