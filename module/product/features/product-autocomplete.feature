Feature: Product autocomplete

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get product autocomplete
    When I send a GET request to "/api/v1/en_GB/products/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product/features/product.json"

  Scenario: Get product autocomplete (order by id)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=id"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product/features/product.json"

  Scenario: Get product autocomplete (order by code)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=code"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product/features/product.json"

  Scenario: Get product autocomplete (order by id)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=id"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product/features/product.json"

  Scenario: Get product autocomplete (order ASC)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=code&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product/features/product.json"

  Scenario: Get product autocomplete (order DESC)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=code&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "product/features/product.json"

  Scenario: Get product autocomplete (search f limit 1)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "product/features/product.json"
