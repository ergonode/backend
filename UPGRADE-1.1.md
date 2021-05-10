UPGRADE FROM 1.0 to 1.1

Attribute:
* The signature of constructor `AttributeValueMapper::__construct(AttributeMapperStrategyInterface ...$strategies)` 
  has been changed to `AttributeValueMapper::__construct(iterable $strategies)`.
* Interface `ValueUpdateStrategyInterface` has been removed
* Interface `ValueInterface`has been extended by method `merge`
* Service `ValueManipulationService` has been removed
* Strategy `StringValueUpdateStrategy` has been removed, solution moved to `StringValue::merge`
* Strategy `StringCollectionValueUpdateStrategy` has been removed, solution moved to `StringCollectionValue::merge`
* Strategy `TranslatableStringValueUpdateStrategy` has been removed, solution moved to `TranslatableStringValue::merge`