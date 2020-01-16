Feature: Workflow

  Scenario: Create first status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow_first_status"

  Scenario: Create second status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "DESTINATION @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow_second_status"

  Scenario: Create status (not authorized)
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "SOURCE @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then unauthorized response is received

  Scenario: Create status (invalid hex color)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#zzZZzz",
        "code": "@@random_code@@"
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (without color)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SOURCE @@random_md5@@",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (without code)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "name": {
          "PL": "PL",
          "EN": "EN"
        },
        "description": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Create status (without description and name)
    Given current authentication token
    Given remember param "duplicated_status_code" with value "DESTINATION_1_@@random_code@@"
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "@duplicated_status_code@"
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then created response is received

  Scenario: Create status (duplicated)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "@duplicated_status_code@"
      }
      """
    When I request "/api/v1/EN/status" using HTTP POST
    Then validation error response is received

  Scenario: Update source status
    Given current authentication token
    Given remember param "duplicated_status_code" with value "DESTINATION_1_@@random_code@@"
    Given the request body is:
      """
      {
        "color": "#ff00ff",
        "name": {
          "PL": "Polish",
          "EN": "English"
        },
        "description": {
          "PL": "Polish",
          "EN": "English"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_first_status@" using HTTP PUT
    Then empty response is received

  Scenario: Update source status (not authorized)
    Given the request body is:
      """
      {
        "color": "#ff00ff"
      }
      """
    When I request "/api/v1/EN/status/@workflow_first_status@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update source status (not found)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff00ff"
      }
      """
    When I request "/api/v1/EN/status/@@random_code@@" using HTTP PUT
    Then not found response is received

  Scenario: Get first status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_first_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_first_status_code"

  Scenario: Get second status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_second_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_second_status_code"

  Scenario: Get statuses
    Given current authentication token
    When I request "/api/v1/EN/status" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (order by id)
    Given current authentication token
    When I request "/api/v1/EN/status?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (order by code)
    Given current authentication token
    When I request "/api/v1/EN/status?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (order by name)
    Given current authentication token
    When I request "/api/v1/EN/status?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (order by description)
    Given current authentication token
    When I request "/api/v1/EN/status?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/status?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/status?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=id%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=code%3DEN" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/status?limit=25&offset=0&filter=description%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get statuses (not authorized)
    When I request "/api/v1/EN/status" using HTTP GET
    Then unauthorized response is received

  Scenario: Set default status
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@workflow_first_status@/default" using HTTP PUT
    Then empty response is received

  Scenario: Set default status (not authorized)
    When I request "/api/v1/EN/workflow/default/status/@workflow_first_status@/default" using HTTP PUT
    Then unauthorized response is received

  Scenario: Set default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/@@random_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete status (not authorized)
    When I request "/api/v1/EN/status/@workflow_first_status_code@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@random_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete first status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_first_status_code@" using HTTP DELETE
    Then conflict response is received

  Scenario: Delete second status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_second_status_code@" using HTTP DELETE
    Then empty response is received
