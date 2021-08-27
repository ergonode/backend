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
