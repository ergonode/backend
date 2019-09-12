Feature: Segment

  Scenario: Create condition set
    Given current authentication token
    Given the request body is:
      """
      {
         "code": "SEGMENT_CONDITION_@@random_uuid@@"
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_conditionset"

  Scenario: Create segment (not authorized)
    When I request "/api/v1/EN/segments" using HTTP POST
    Then unauthorized response is received

  Scenario: Create segment
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_1_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
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
    And remember response param "id" as "segment"

  Scenario: Create segment (without name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received

  Scenario: Create segment (without description and name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@segment_conditionset@"
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received

  Scenario: Create segment (without code)
    Given current authentication token
    Given the request body is:
      """
      {
        "condition_set_id": "@segment_conditionset@",
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
    Then validation error response is received

  Scenario: Create segment (without condition set)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_2_@@random_code@@"
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then validation error response is received

  Scenario: Update segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update segment
    Given current authentication token
    Given the request body is:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "PL": "Segment (changed)",
          "EN": "Segment (changed)"
        },
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Update segment (without name)
    Given current authentication token
    Given the request body is:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Update segment (without name and description)
    Given current authentication token
    Given the request body is:
      """
      {
        "condition_set_id": "@segment_conditionset@"
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Update segment (without condition set)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "PL": "Segment (changed)",
          "EN": "Segment (changed)"
        },
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then validation error response is received

  Scenario: Get segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get segment
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment@" using HTTP GET
    Then the response code is 200

  Scenario: Get segments
    Given current authentication token
    When I request "/api/v1/EN/segments" using HTTP GET
    Then grid response is received

  Scenario: Get segments (not authorized)
    When I request "/api/v1/EN/segments" using HTTP GET
    Then unauthorized response is received

  Scenario: Get segments (order by code)
    Given current authentication token
    When I request "/api/v1/EN/segments?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get segments (order by name)
    Given current authentication token
    When I request "/api/v1/EN/segments?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get segments (order by description)
    Given current authentication token
    When I request "/api/v1/EN/segments?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get segments (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/segments?limit=25&offset=0&filter=code%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get segments (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/segments?limit=25&offset=0&filter=name%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get segments (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/segments?limit=25&offset=0&filter=description%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Delete segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete segment
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment@" using HTTP DELETE
    Then empty response is received
