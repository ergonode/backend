Feature: collection type autocomplete

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get collection type  autocomplete
    When I send a GET request to "/api/v1/en_GB/collections/type/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product-collection/features/collection.json"

  Scenario: Get collection type autocomplete (order by label)
    When I send a GET request to "/api/v1/en_GB/collections/type/autocomplete?field=label"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product-collection/features/collection.json"

  Scenario: Get collection type autocomplete (order ASC)
    When I send a GET request to "/api/v1/en_GB/collections/type/autocomplete?field=label&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product-collection/features/collection.json"

  Scenario: Get collection type autocomplete (order DESC)
    When I send a GET request to "/api/v1/en_GB/collections/type/autocomplete?field=label&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product-collection/features/collection.json"

  Scenario: Get collection type autocomplete (search f limit 1)
    When I send a GET request to "/api/v1/en_GB/collections/type/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "product-collection/features/collection.json"
