Feature: Segment autocomplete

  Scenario: Get segment autocomplete
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "segment/features/segment.json"

  Scenario: Get segment autocomplete (not authorized)
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete"
    Then the response status code should be 401

  Scenario: Get segment autocomplete (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete?field=code"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "segment/features/segment.json"

  Scenario: Get segment autocomplete (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete?field=name"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "segment/features/segment.json"

  Scenario: Get segment autocomplete (order by label)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete?field=label"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "segment/features/segment.json"

  Scenario: Get segment autocomplete (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete?field=code&order=ASC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "segment/features/segment.json"

  Scenario: Get segment autocomplete (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete?field=code&order=DESC"
    Then the response status code should be 200
    And the JSON should be valid according to the schema "segment/features/segment.json"

  Scenario: Get segment autocomplete (search f limit 1)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete?search=f&limit=1"
    And the JSON should be valid according to the schema "segment/features/segment.json"
