UPGRADE FROM 1.1 to 1.2

Core\Test:
* The signature of constructor `ApiAuthContext` has changed
    * For the context to work it does require BehatchBridgeExtension enabled in Behat configuration
* The signature of constructor `ApiAuthTokenContext` has changed
    * For the context to work it does require BehatchBridgeExtension enabled in Behat configuration

ExporterFile:
* The signature of constructor `StartProcessCommandHandler::__construct(ExportRepositoryInterface $repository, TempFileStorage $storage, ExportProductBuilder $productBuilder, ExportTemplateElementBuilder $templateElementBuilder, ExportAttributeBuilder $attributeBuilder, ExportOptionBuilder $optionBuilder, ExportCategoryBuilder $categoryBuilder, ExportTemplateBuilder $templateBuilder)`
  has been changed to `StartProcessCommandHandler::__construct(ExportRepositoryInterface $repository, TempFileStorage $storage, iterable $builders)` 
* The signature of constructor `ProcessExportCommandHandler::__construct(ChannelRepositoryInterface $channelRepository, ExportRepositoryInterface $exportRepository, CommandBusInterface $commandBus, array $steps)`
  has been changed to `ProcessExportCommandHandler::__construct(ChannelRepositoryInterface $channelRepository, ExportRepositoryInterface $exportRepository, CommandBusInterface $commandBus, iterable $steps)` 
* Interface `ExportHeaderBuilderInterface` has been added
* Classes `ExportProductBuilder`, `ExportTemplateElementBuilder`, `ExportAttributeBuilder`, `ExportOptionBuilder`, `ExportCategoryBuilder`, `ExportTemplateBuilder` implements `ExportHeaderBuilderInterface`
