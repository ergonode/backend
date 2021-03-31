Feature: Account module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get profile log (order by author)
    When I send a GET request to "/api/v1/en_GB/profile/log?field=author"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get profile log (order by recorded_at)
    When I send a GET request to "/api/v1/en_GB/profile/log?field=recorded_at"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get profile log (order by event)
    When I send a GET request to "/api/v1/en_GB/profile/log?field=event"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get profile log (filter by time)
    When I send a GET request to "/api/v1/en_GB/profile/log?limit=25&offset=0&filter=recorded_at%3D2019"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get profile log (filter by author)
    When I send a GET request to "/api/v1/en_GB/profile/log?limit=25&offset=0&filter=author%3DSystem"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts log (order by author)
    When I send a GET request to "/api/v1/en_GB/accounts/log?field=author"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].author" should exist

  Scenario: Get accounts log (order by recorded_at)
    When I send a GET request to "/api/v1/en_GB/accounts/log?field=recorded_at"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].recorded_at" should exist

  Scenario: Get accounts log (order by event)
    When I send a GET request to "/api/v1/en_GB/accounts/log?field=event"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].event:en_GB" should exist

  Scenario: Get accounts log (filter by time)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by null time)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at="
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by time lower or equal 2000-01-01)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at<=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by time lower or equal 2000-01-01)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at>=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get accounts log (filter by time greater or equal 2050-01-01)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at>=2050-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by time greater or equal 2050-01-01)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at<=2050-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get accounts log (filter by time in range 2000-01-01 -2050-01-01)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=recorded_at<=2050-01-01;recorded_at>=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get accounts log (filter by author)
    When I send a GET request to "/api/v1/en_GB/accounts/log?limit=25&offset=0&filter=author%3DSystem"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
