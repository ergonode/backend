Feature: Category tree autocomplete

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get category tree autocomplete
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "category/features/category.json"

  Scenario: Get category tree autocomplete (order by code)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete?field=code"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "category/features/category.json"

  Scenario: Get category tree autocomplete (order by name)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete?field=name"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "category/features/category.json"

  Scenario: Get category tree autocomplete (order by label)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete?field=label"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "category/features/category.json"

  Scenario: Get category tree autocomplete (order ASC)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete?field=code&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "category/features/category.json"

  Scenario: Get category tree autocomplete (order DESC)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete?field=code&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "category/features/category.json"

  Scenario: Get category tree autocomplete (search f limit 1)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "category/features/category.json"
