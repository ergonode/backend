Feature: Core module - language

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get translation language
    When I send a GET request to "/api/v1/en_GB/languages/en_GB"
    Then the response status code should be 200

  Scenario: Get translation language (not found)
    When I send a GET request to "/api/v1/en_GB/languages/ZZ"
    Then the response status code should be 404

  Scenario: Get languages (order by code)
    When I send a GET request to "/api/v1/en_GB/languages?field=code"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order by label)
    When I send a GET request to "/api/v1/en_GB/languages?field=label"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order by active)
    When I send a GET request to "/api/v1/en_GB/languages?field=active"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order ASC)
    When I send a GET request to "/api/v1/en_GB/languages?field=label&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (order DESC)
    When I send a GET request to "/api/v1/en_GB/languages?field=label&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by code)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=code%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by label)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=label%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by iso)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=iso%3Den"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get languages (filter by active)
    When I send a GET request to "/api/v1/en_GB/languages?limit=25&offset=0&filter=active%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
