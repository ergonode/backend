Feature: channel module

  Scenario: Create condition set
    Given current authentication token
    Given the request body is:
      """
      {
        "conditions": []
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "condition_set_id"

  Scenario: Create segment
    Given remember param "segment_code" with value "SEG_1_@@random_code@@"
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@condition_set_id@",
        "name": {
          "PL": "Segment",
          "EN": "Segment"
        },
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_id"

  Scenario: Get segment
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment_id@" using HTTP GET
    Then the response code is 200


  Scenario: Create channel
    Given current authentication token
    Given the request body is:
      """
      {
        "segment_id": "@segment_id@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/channels" using HTTP POST
    Then created response is received
    And remember response param "id" as "channel"

  Scenario: Create channel (not authorized)
    When I request "/api/v1/EN/channels" using HTTP POST
    Then unauthorized response is received

  Scenario: Create channel (no Name)
    Given current authentication token
    Given the request body is:
      """
      {
        "segment_id": "@segment_id@"
      }
      """
    When I request "/api/v1/EN/channels" using HTTP POST
    Then created response is received

  Scenario: Create channel (empty Name)
    Given current authentication token
    Given the request body is:
      """
      {
        "segment_id": "@segment_id@",
        "name": {}
      }
      """
    When I request "/api/v1/EN/channels" using HTTP POST
    Then created response is received

  Scenario: Create channel (name with language with empty string value)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "DE": "",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/channels" using HTTP POST
    Then validation error response is received

  Scenario: Update channel
    Given current authentication token
    Given the request body is:
      """
      {
        "segment_id": "@segment_id@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/channels/@channel@" using HTTP PUT
    Then empty response is received

  Scenario: Update channel (not authorized)
    When I request "/api/v1/EN/channels/@channel@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update channel (not found)
    Given current authentication token
    When I request "/api/v1/EN/channels/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update channel (empty name)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
        }
      }
      """
    When I request "/api/v1/EN/channels/@channel@" using HTTP PUT
    Then validation error response is received

  Scenario: Update channel (wrong parameter)
    Given current authentication token
    Given the request body is:
      """
      {
        "test": {
        }
      }
      """
    When I request "/api/v1/EN/channels/@channel@" using HTTP PUT
    Then validation error response is received

  Scenario: Update channel (empty translation)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "DE": "",
          "EN": "Test EN (changed)"
        }
      }
      """
    When I request "/api/v1/EN/channels/@channel@" using HTTP PUT
    Then validation error response is received

  Scenario: Get channel
    Given current authentication token
    When I request "/api/v1/EN/channels/@channel@" using HTTP GET
    Then the response code is 200

  Scenario: Get channel (not authorized)
    When I request "/api/v1/EN/channels/@channel@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get channel (not found)
    Given current authentication token
    When I request "/api/v1/EN/channels/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Delete channel (not authorized)
    When I request "/api/v1/EN/channels/@channel@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete channel (not found)
    Given current authentication token
    When I request "/api/v1/EN/channels/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete channel
    Given current authentication token
    When I request "/api/v1/EN/channels/@channel@" using HTTP DELETE
    Then empty response is received

  Scenario: Get channels (order by name)
    Given current authentication token
    When I request "/api/v1/EN/channels?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get channels (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/channels?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get channels (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/channels?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get channels (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/channels?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get channels (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/channels?limit=25&offset=0&filter=code%3DCAT" using HTTP GET
    Then grid response is received

  Scenario: Get channels (not authorized)
    When I request "/api/v1/EN/channels" using HTTP GET
    Then unauthorized response is received
