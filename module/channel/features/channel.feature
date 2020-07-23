Feature: channel module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get channel type dictionary
    When I send a GET request to "/api/v1/en/dictionary/channels"
    Then the response status code should be 200

  Scenario: Get channels (order ASC)
    When I send a GET request to "/api/v1/en/channels?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the response status code should be 200

  Scenario: Get channels (order DESC)
    When I send a GET request to "/api/v1/en/channels?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the response status code should be 200

  Scenario: Get channels (filter by name)
    When I send a GET request to "/api/v1/en/channels?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the response status code should be 200

  Scenario: Get channels (filter by code)
    When I send a GET request to "/api/v1/en/channels?limit=25&offset=0&filter=code%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the response status code should be 200
