Feature: channel module

  Scenario: Create condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/en/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_set_id"

  Scenario: Create segment
    Given remember param "segment_code" with value "SEG_1_@@random_code@@"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/segments" with body:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@condition_set_id@",
        "name": {
          "pl_PL": "Segment",
          "pl_PL": "Segment"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "pl_PL": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_id"

  Scenario: Get segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/segments/@segment_id@"
    Then the response status code should be 200


  Scenario: Create channel
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "segment_id": "@segment_id@",
        "name": {
          "de": "Test de",
          "pl_PL": "Test pl_PL"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "channel"

  Scenario: Create channel (not authorized)
    When I send a POST request to "/api/v1/en/channels"
    Then the response status code should be 401

  Scenario: Create channel (no Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "segment_id": "@segment_id@"
      }
      """
    Then the response status code should be 201

  Scenario: Create channel (empty Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "segment_id": "@segment_id@",
        "name": {}
      }
      """
    Then the response status code should be 201

  Scenario: Create channel (name with language with empty string value)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "name": {
          "de": "",
          "pl_PL": "Test pl_PL"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update channel
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/channels/@channel@" with body:
      """
      {
        "segment_id": "@segment_id@",
        "name": {
          "de": "Test de",
          "pl_PL": "Test pl_PL"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update channel (not authorized)
    When I send a PUT request to "/api/v1/en/channels/@channel@"
    Then the response status code should be 401

  Scenario: Update channel (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/channels/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update channel (empty name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/channels/@channel@" with body:
      """
      {
        "name": {
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update channel (wrong parameter)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/channels/@channel@" with body:
      """
      {
        "test": {
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update channel (empty translation)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/channels/@channel@" with body:
      """
      {
        "name": {
          "de": "",
          "pl_PL": "Test pl_PL (changed)"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get channel
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/pl_PL/channels/@channel@"
    Then the response status code should be 200

  Scenario: Get channel (not authorized)
    When I send a GET request to "/api/v1/en/channels/@channel@"
    Then the response status code should be 401

  Scenario: Get channel (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/channels/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete channel (not authorized)
    When I send a DELETE request to "/api/v1/en/channels/@channel@"
    Then the response status code should be 401

  Scenario: Delete channel (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/channels/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete channel
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/channels/@channel@"
    Then the response status code should be 204

  Scenario: Get channels (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/channels?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get channels (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/channels?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get channels (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/channels?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get channels (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/channels?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get channels (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/channels?limit=25&offset=0&filter=code%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get channels (not authorized)
    When I send a GET request to "/api/v1/en/channels"
    Then the response status code should be 401
