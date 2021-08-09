UPGRADE FROM 1.1 to 1.2

Core\Test:
* The signature of constructor `ApiAuthContext` has changed
    * For the context to work it does require BehatchBridgeExtension enabled in Behat configuration
* The signature of constructor `ApiAuthTokenContext` has changed
    * For the context to work it does require BehatchBridgeExtension enabled in Behat configuration

ExporterFile:
* The signature of constructor `StartProcessCommandHandler` has changed  
* The signature of constructor `ProcessExportCommandHandler` has changed

BatchAction: 
* The signature of constructor `ProcessBatchActionEntryCommand` has changed