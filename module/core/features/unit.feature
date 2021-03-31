Feature: Core module - unit

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create unit
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

  Scenario: Create unit 2
    And remember param "unit_name_2" with value "@@random_md5@@"
    And remember param "unit_symbol_2" with value "@@random_symbol@@"
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@unit_name_2@",
        "symbol": "@unit_symbol_2@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "unit_id_2"

  Scenario: Create unit (name duplicated)
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@unit_name_1@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (symbol duplicated)
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@symbol_name_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (no Name)
    When I send a POST request to "/api/v1/en_GB/units" with body:
      """
      {
         "symbol": "nu1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (no symbol)
    When I send a POST request to "/api/v1/en_GB/units" with body:
      """
      {
         "name": "New Unit 1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (empty name)
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "",
        "symbol": "nu1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (empty symbol)
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "New Unit 1",
        "symbol": ""
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (name too long)
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "uCqYeiPvC5K4LzhG9NWm46kdHedyS8ws9lyfba2FRjums6h9BDe53fAwmmWa2UWwRTWRY79fSGfeJQgYr4OmunZKGjengx1kTEhDnVrQnGw8rv8uKOBkbiSSLILIWkezNxgs3cIJA88NVyTy6BEanYgb5OFVm2F8dRMCxUTAFmHhosDZrhVxcVZNORO9jzRtnTYwSmBmcgRKWmgXMry3ma0u3B8j49TUycDaBiyq4IW9PgBQjtEgpn3zD2Btxely",
        "symbol": "nu1"
      }
      """
    Then the response status code should be 400

  Scenario: Create unit (symbol too long)
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "New Unit 1",
        "symbol": "uCqYeiPvC5K4LzhG9NWm46kdHedyS8ws9lyfba2FRjums6h9BDe53fAwmmWa2UWwRTWRY79fSGfeJQgYr4OmunZKGjengx1kTEhDnVrQnGw8rv8uKOBkbiSSLILIWkezNxgs3cIJA88NVyTy6BEanYgb5OFVm2F8dRMCxUTAFmHhosDZrhVxcVZNORO9jzRtnTYwSmBmcgRKWmgXMry3ma0u3B8j49TUycDaBiyq4IW9PgBQjtEgpn3zD2Btxely"
      }
      """
    Then the response status code should be 400

  Scenario: Update unit (with the same name)
    When I send a PUT request to "/api/v1/en_GB/units/@unit_id_1@" with body:
      """
      {
        "name": "@unit_name_1@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 204

  Scenario: Update unit (with existing name)
    When I send a PUT request to "/api/v1/en_GB/units/@unit_id_1@" with body:
      """
      {
        "name": "@unit_name_2@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 400

  Scenario: Update unit (with the same symbol)
    When I send a PUT request to "/api/v1/en_GB/units/@unit_id_1@" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@unit_symbol_1@"
      }
      """
    Then the response status code should be 204

  Scenario: Update unit (with existing symbol)
    When I send a PUT request to "/api/v1/en_GB/units/@unit_id_1@" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@unit_symbol_2@"
      }
      """
    Then the response status code should be 400

  Scenario: Update unit (not found)
    When I send a PUT request to "/api/v1/en_GB/units/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get unit
    When I send a GET request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 200

  Scenario: Get unit (not found)
    When I send a GET request to "/api/v1/en_GB/unites/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete unit (not found)
    When I send a DELETE request to "/api/v1/en_GB/units/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete unit
    When I send a DELETE request to "/api/v1/en_GB/units/@unit_id_1@"
    Then the response status code should be 204

  Scenario: Get units (order by code)
    When I send a GET request to "/api/v1/en_GB/units?field=name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get units (order by code)
    When I send a GET request to "/api/v1/en_GB/units?field=symbol"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get units (order ASC)
    When I send a GET request to "/api/v1/en_GB/units?field=name&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get units (order DESC)
    When I send a GET request to "/api/v1/en_GB/units?field=name&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get units (filter by name)
    When I send a GET request to "/api/v1/en_GB/units?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get units (filter by code)
    When I send a GET request to "/api/v1/en_GB/units?limit=25&offset=0&filter=symbol%3DCAT"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
