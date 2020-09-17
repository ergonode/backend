Feature: Core module - unit

  Scenario: Create unit
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And remember param "unit_name_1" with value "@@random_md5@@"
    And remember param "symbol_name_1" with value "@@random_symbol@@"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@unit_name_1@",
        "symbol": "@symbol_name_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "unit_id_1"

  Scenario: Create unit (not authorized)
    When I send a POST request to "/api/v1/en_GB/units"
    Then the response status code should be 401

  Scenario: Create unit (name duplicated)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@unit_name_1@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (symbol duplicated)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@symbol_name_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (no Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/units" with body:
      """
      {
         "symbol": "nu1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (no symbol)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/units" with body:
      """
      {
         "name": "New Unit 1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (empty name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "",
        "symbol": "nu1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (empty symbol)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "New Unit 1",
        "symbol": ""
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (name too long)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "uCqYeiPvC5K4LzhG9NWm46kdHedyS8ws9lyfba2FRjums6h9BDe53fAwmmWa2UWwRTWRY79fSGfeJQgYr4OmunZKGjengx1kTEhDnVrQnGw8rv8uKOBkbiSSLILIWkezNxgs3cIJA88NVyTy6BEanYgb5OFVm2F8dRMCxUTAFmHhosDZrhVxcVZNORO9jzRtnTYwSmBmcgRKWmgXMry3ma0u3B8j49TUycDaBiyq4IW9PgBQjtEgpn3zD2Btxely",
        "symbol": "nu1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (symbol too long)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "New Unit 1",
        "symbol": "uCqYeiPvC5K4LzhG9NWm46kdHedyS8ws9lyfba2FRjums6h9BDe53fAwmmWa2UWwRTWRY79fSGfeJQgYr4OmunZKGjengx1kTEhDnVrQnGw8rv8uKOBkbiSSLILIWkezNxgs3cIJA88NVyTy6BEanYgb5OFVm2F8dRMCxUTAFmHhosDZrhVxcVZNORO9jzRtnTYwSmBmcgRKWmgXMry3ma0u3B8j49TUycDaBiyq4IW9PgBQjtEgpn3zD2Btxely"
      }
      """
    Then the response status code should be 400

  Scenario: Update unit
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/units/@unit_id_1@" with body:
      """
      {
        "name": "Name changed",
        "symbol": "nc1"
      }
      """
    Then the response status code should be 204

  Scenario: Update unit (not authorized)
    When I send a PUT request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 401

  Scenario: Update unit (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/units/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get unit
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 200

  Scenario: Get unit (not authorized)
    When I send a GET request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 401

  Scenario: Get unit (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/unites/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete unit (not authorized)
    When I send a DELETE request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 401

  Scenario: Delete unit (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/units/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete unit
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 204

  Scenario: Get units (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get units (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units?field=symbol"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get units (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get units (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get units (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get units (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/units?limit=25&offset=0&filter=symbol%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get units (not authorized)
    When I send a GET request to "/api/v1/en_GB/units"
    Then the response status code should be 401
