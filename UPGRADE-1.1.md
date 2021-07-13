UPGRADE FROM 1.0 to 1.1

Attribute:
* The signature of constructor `AttributeValueMapper::__construct(AttributeMapperStrategyInterface ...$strategies)` 
  has been changed to `AttributeValueMapper::__construct(iterable $strategies)`.
* Interface `ValueUpdateStrategyInterface` has been removed
* Interface `ValueInterface`has been extended by method `merge`
* Class `ValueManipulationService` has been removed
* Class `StringValueUpdateStrategy` has been removed, solution moved to `StringValue::merge`
* Class `StringCollectionValueUpdateStrategy` has been removed, solution moved to `StringCollectionValue::merge`
* Class `TranslatableStringValueUpdateStrategy` has been removed, solution moved to `TranslatableStringValue::merge`
* Endpoint `[PUT] /api/v1/{language}/attributes/{attribute}/options/{option}` returns 200 instead of 201

Core:
* Namespace `Ergonode\Core\Application\Form\Type\CurrencyFormType` has been fixed to `Ergonode\Attribute\Application\Form\Type\CurrencyFormType`

Product:
* Class `AddProductBindingCommand` constructor has changed
* Class `RemoveProductBindingCommand` constructor has changed
* Class `AddProductChildCommand` constructor has changed
* Class `AddProductChildrenBySegmentsCommand` constructor has changed
* Class `AddProductChildrenCommand` constructor has changed
* Class `RemoveProductChildCommand` constructor has changed
* Endpoint `[PATCH] /api/v1/{language}/products/attributes` returns 204 instead of 200

Importer:
* Endpoint `[PUT] /api/v1/{language}/sources/{source}` returns 200 instead of 201
* Class `ImportSimpleProductCommandHandler` constructor has changed
* Class `ImportGroupingProductCommandHandler` constructor has changed
* Class `ImportVariableProductCommandHandler` constructor has changed

Notification
* Endpoint `[POST] /api/v1/profile/notifications/mark-all` returns 204 instead of 202
* Endpoint `[POST] /api/v1/profile/notifications/{notification}/mark` returns 204 instead of 202

Workflow
* Endpoint `[PUT] /api/v1/{language}/workflow/default` returns 200 instead of 201

Multimedia
* Class `UploadMultimediaAction` constructor has changed
