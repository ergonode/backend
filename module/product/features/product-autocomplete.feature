Feature: Product autocomplete

  Scenario: Get product autocomplete
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/product/features/product.json"

  Scenario: Get product autocomplete (not authorized)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete"
    Then the response status code should be 401

  Scenario: Get product autocomplete (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=id"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/product/features/product.json"

  Scenario: Get product autocomplete (order by sku)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=sku"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/product/features/product.json"

  Scenario: Get product autocomplete (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=id"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/product/features/product.json"

  Scenario: Get product autocomplete (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=sku&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/product/features/product.json"

  Scenario: Get product autocomplete (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?field=sku&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "module/product/features/product.json"

  Scenario: Get product autocomplete (search f limit 1)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/products/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "module/product/features/product.json"
