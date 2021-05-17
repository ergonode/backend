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

Product:
* Class `AddProductBindingCommand` constructor has changed
* Class `RemoveProductBindingCommand` constructor has changed
* Class `AddProductChildCommand` constructor has changed
* Class `AddProductChildrenBySegmentsCommand` constructor has changed
* Class `AddProductChildrenCommand` constructor has changed
* Class `RemoveProductChildCommand` constructor has changed
