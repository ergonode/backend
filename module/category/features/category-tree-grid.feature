Feature: Category tree module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get category trees (order by name)
    When I send a GET request to "/api/v1/en_GB/trees?field=name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees
    When I send a GET request to "/api/v1/en_GB/trees"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (order by id)
    When I send a GET request to "/api/v1/en_GB/trees?field=id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (order by code)
    When I send a GET request to "/api/v1/en_GB/trees?field=code"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (order by name)
    When I send a GET request to "/api/v1/en_GB/trees?field=name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (order ASC)
    When I send a GET request to "/api/v1/en_GB/trees?field=name&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (order DESC)
    When I send a GET request to "/api/v1/en_GB/trees?field=name&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (filter by name)
    When I send a GET request to "/api/v1/en_GB/trees?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (filter by code)
    When I send a GET request to "/api/v1/en_GB/trees?limit=25&offset=0&filter=code%3DCAT"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get category trees (filter by name)
    When I send a GET request to "/api/v1/en_GB/trees?limit=25&offset=0&filter=name%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
