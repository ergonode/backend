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
* Class `AddProductBindingCommand` has change constructor
* Class `RemoveProductBindingCommand` has change constructor
* Class `AddProductChildCommand` has change constructor
* Class `AddProductChildrenBySegmentsCommand` has change constructor
* Class `AddProductChildrenCommand` has change constructor
* Class `RemoveProductChildCommand` has change constructor
