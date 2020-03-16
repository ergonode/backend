Feature: Account module

  Scenario: Get profile log (order by author)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/profile/log?field=author"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get profile log (order by recorded_at)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/profile/log?field=recorded_at"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get profile log (order by event)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/profile/log?field=event"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get profile log (filter by time)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/profile/log?limit=25&offset=0&filter=recorded_at%3D2019"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get profile log (filter by author)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/profile/log?limit=25&offset=0&filter=author%3DSystem"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get profile log (not authorized)
    When I send a GET request to "/api/v1/EN/profile/log"
    Then the response status code should be 401

  Scenario: Get accounts log (order by author)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?field=author"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].author" should exist

  Scenario: Get accounts log (order by recorded_at)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?field=recorded_at"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].recorded_at" should exist

  Scenario: Get accounts log (order by event)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?field=event"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].event:EN" should exist

  Scenario: Get accounts log (filter by time)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at=2000-01-01"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by null time)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at="
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by time lower or equal 2000-01-01)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at<=2000-01-01"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by time lower or equal 2000-01-01)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at>=2000-01-01"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get accounts log (filter by time greater or equal 2050-01-01)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at>=2050-01-01"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get accounts log (filter by time greater or equal 2050-01-01)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at<=2050-01-01"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get accounts log (filter by time in range 2000-01-01 -2050-01-01)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at<=2050-01-01;recorded_at>=2000-01-01"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get accounts log (filter by author)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/log?limit=25&offset=0&filter=author%3DSystem"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts log (not authorized)
    When I send a GET request to "/api/v1/EN/accounts/log"
    Then the response status code should be 401
