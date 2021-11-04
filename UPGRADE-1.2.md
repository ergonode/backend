UPGRADE FROM 1.1 to 1.2

Core\Test:
* The signature of constructor `ApiAuthContext` has changed
    * For the context to work it does require BehatchBridgeExtension enabled in Behat configuration
* The signature of constructor `ApiAuthTokenContext` has changed
    * For the context to work it does require BehatchBridgeExtension enabled in Behat configuration

ExporterFile:
* The signature of constructor `StartProcessCommandHandler` has changed  
* The signature of constructor `ProcessExportCommandHandler` has changed
* Column `_code` added to exported `templates_elements.csv` file, field '_name' become deprecated, will be removed in future version
* Column `_code` added to exported `templates.csv` file

Designer:
* The signature of constructor `Template` has changed
* The signature of constructor `TemplateCreateEvent` has changed
* * The signature of constructor `CreateTemplateCommand` has changed

BatchAction: 
* The signature of constructor `ProcessBatchActionEntryCommand` has changed
* Class `EndBatchActionCommand` has been removed
* Class `StartBatchActionCommand` has been removed
* Class `EndBatchActionCommandHandler` has been removed
* Class `StartBatchActionCommandHandler` has been removed
* Rabbit queue configuration MESSENGER_TRANSPORT_BATCH_ACTION_DSN is no longer required

Attribute:
* Validation `AttributeCode` value cannot be 'id' or start with 'esa_' - *Before migration to new version you need to change all `AttributeCodes` which start with 'esa_' or equal 'id'. Otherwise, it can cause system to break.*

Importer:
* The signature of constructor `ImportProductImageAttributeStrategy` has changed
* The signature of constructor `MultimediaFromUrlImportAction` has changed  

Multimedia:
* The signature of constructor `UpdateMultimediaCommandHandler` has changed  
* The signature of constructor `DeleteMultimediaCommandHandler` has changed  

Segment:
* The signature of constructor `SegmentProductService` has changed
