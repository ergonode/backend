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

  Scenario: Get first status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_first_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_first_status_code"

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_second_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_second_status_code"

  Scenario: Set default status
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@workflow_first_status_code@/default" using HTTP PUT
    Then empty response is received

  Scenario: Set default status (not authorized)
    When I request "/api/v1/EN/workflow/default/status/@workflow_first_status_code@/default" using HTTP PUT
    Then unauthorized response is received

  Scenario: Set default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/not_exists_status" using HTTP DELETE
    Then not found response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_first_status_code@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_second_status_code@" using HTTP DELETE
    Then empty response is received
