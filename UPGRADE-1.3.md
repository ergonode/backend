UPGRADE FROM 1.2 to 1.3

Attribute:
* Method `AttributeId:fromKey` become deprecated, will be removed in future version
* The signature of constructor `ProductReadInheritedValuesByLanguageAction` has changed
* The signature of constructor `DbalCategoryCreatedEventProjector` has changed
* The signature of constructor `DbalValueAddedEventProjector` has changed
* The signature of constructor `DbalValueChangedEventProjector` has changed 
* The signature of constructor `DbalValueRemovedEventProjector` has changed
* The signature of constructor `DbalProductValueAddedEventProjector` has changed
* The signature of constructor `DbalProductValueChangedEventProjector` has changed
* The signature of constructor `DbalProductValueRemovedEventProjector` has changed
* The signature of constructor `ProductWorkflowQuery` has changed
* The signature of constructor `AbstractOption` has changed
* The signature of constructor `OptionCreatedEvent` has changed

Core
* `DownloaderInterface::download` throws exception rather than returning `null` value

Workflow 
* Signature of method `Ergonode\Workflow\Domain\Provider\WorkflowProvider` has changed 
* Method `Ergonode\Workflow\Domain\Entity\AbstractWorkflow::getSortedTransitionStatuses` become deprecated
* Instead of using `Ergonode\Workflow\Domain\Provider\WorkflowProvider`, services now use `Ergonode\Workflow\Domain\Provider\WorkflowProviderinterface`
* Endpoint `[GET] api/v1/en_GB/workflow/default/transitions` grid column name `source` change to `from`, `destination` change to `to`
* Endpoint `[POST] api/v1/en_GB/workflow/default/transitions` property `source` change to `from`, `destination` change to `to`
* Endpoint `[POST] api/v1/workflow` property `condition_set_id` was removed
* Column `source_id` in table `workflow_transition` was change to `from_id`
* Column `destination_id` in table `workflow_transition` was change to `to_id`
* method `getSource` and `getDestination` of class `AddWorkflowTransitionCommand` become deprecated
* method `getSource` and `getDestination` of class `DeleteWorkflowTransitionCommand` become deprecated
* method `getSource` and `getDestination` of class `UpdateWorkflowTransitionCommand` become deprecated
* method `getSource` and `getDestination` of class `WorkflowTransitionRemovedEvent` become deprecated
* The signature of constructor `CreateWorkflowCommand` has changed
* The signature of constructor `UpdateWorkflowCommand` has changed
* Limitation of conditions in Workflow Transition.
* `Transition` class has change to simple ValueObject, 
* methods `getConditionSetId`, `changeConditionSetId` and `changeRoleIds` of class `Transition` was removed  
* The signature of constructor `Transition` has changed
* method `changeTransitionConditionSetId` of class `AbstractWorkflow` was removed
* New messenger transport `StatusTransport` was added, `status` receiver is required for setting product statuses for new added languages

Value
* `getTransation` and `hasTransation` methods are added to `Ergonode\Value\Domain\ValueObject\ValueInterface`
