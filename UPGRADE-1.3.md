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

Workflow:
* Endpoint `[GET] api/v1/en_GB/workflow/default/transitions` grid column name `source` change to `from`, `destination` change to `to`
* Endpoint `[POST] api/v1/en_GB/workflow/default/transitions` property `source` change to `from`, `destination` change to `to`
* Column `source_id` in table `workflow_transition` was change to `from_id`
* Column `destination_id` in table `workflow_transition` was change to `to_id`
* method `getSource` and `getDestination` of class `AddWorkflowTransitionCommand` become deprecated
* method `getSource` and `getDestination` of class `DeleteWorkflowTransitionCommand` become deprecated
* method `getSource` and `getDestination` of class `UpdateWorkflowTransitionCommand` become deprecated
* method `getSource` and `getDestination` of class `WorkflowTransitionRemovedEvent` become deprecated