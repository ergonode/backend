Feature: Workflow

  Scenario: Create default status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "ST @@random_md5@@",
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
    And remember response param "id" as "workflow_status"

  Scenario: Create default status (not authorized)
    When I request "/api/v1/EN/status" using HTTP POST
    Then unauthorized response is received

  Scenario: Update default status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "ST @@random_md5@@",
        "name": {
          "PL": "PL (changed)",
          "EN": "EN (changed)"
        },
        "description": {
          "PL": "PL (changed)",
          "EN": "EN (changed)"
        }
      }
      """
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then empty response is received

  Scenario: Update default status (not authorized)
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP GET
    Then the response code is 200

  Scenario: Get default status (not authorized)
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Update default workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "TEST_@@random_code@@",
        "statuses": ["@workflow_status@"],
        "transitions": []
      }
    """
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then empty response is received

  Scenario: Update default workflow (wrong status)
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "TEST_@@random_code@@",
        "statuses": ["test"],
        "transitions": []
      }
    """
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then validation error response is received

  Scenario: Get default statuses
    Given current authentication token
    When I request "/api/v1/EN/status" using HTTP GET
    Then grid response is received

  Scenario: Get default statuses (not authorized)
    When I request "/api/v1/EN/status" using HTTP GET
    Then unauthorized response is received

  Scenario: Create workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "WRK_@@random_code@@",
        "statuses": ["@workflow_status@"],
        "transitions": []
      }
    """
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow"

  Scenario: Create workflow (wrong statuses)
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "WRK_@@random_code@@",
        "statuses": ["test"],
        "transitions": []
      }
    """
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then validation error response is received

  Scenario: Create workflow (not authorized)
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then unauthorized response is received

  Scenario: Update default workflow (not authorized)
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then unauthorized response is received

  Scenario: Get default workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default" using HTTP GET
    Then the response code is 200

  Scenario: Get default workflow (not authorized)
    When I request "/api/v1/EN/workflow/default" using HTTP GET
    Then unauthorized response is received

  Scenario: Delete workflow (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/@static_uuid@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete workflow (not authorized)
    When I request "/api/v1/EN/workflow/@workflow@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/@workflow@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete default status (not authorized)
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@static_uuid@@" using HTTP DELETE
    Then not found response is received
