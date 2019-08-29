Feature: Workflow

  Scenario: Create workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "TEST_@@random_code@@",
        "statuses": [
          {
            "color": "#ff0000",
            "code": "in progress",
            "name": {
              "PL": "Translated phase PL",
              "EN": "Translated phase EN"
            },
            "description": {
              "PL": "Translated phase PL",
              "EN": "Translated phase EN"
            }
          }
        ]
      }
    """
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow"

  Scenario: Create workflow (not authorized)
    When I request "/api/v1/EN/workflow" using HTTP POST
    Then unauthorized response is received

  Scenario: Update default workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "TEST_@@random_code@@",
        "statuses": [
          {
            "color": "#ff0000",
            "code": "in progress",
            "name": {
              "PL": "PL",
              "EN": "EN"
            },
            "description": {
              "PL": "PL",
              "EN": "EN"
            }
          }
        ]
      }
    """
    When I request "/api/v1/EN/workflow/default" using HTTP PUT
    Then created response is received

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

  Scenario: Create default status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "in progress",
        "name": {
          "PL": "Translated phase PL",
          "EN": "Translated phase EN"
        },
        "description": {
          "PL": "Translated phase PL",
          "EN": "Translated phase EN"
        }
      }
      """
    When I request "/api/v1/EN/workflow/default/status" using HTTP POST
    Then created response is received
    And remember response param "id" as "workflow_status"

  Scenario: Create default status (not authorized)
    When I request "/api/v1/EN/workflow/default/status" using HTTP POST
    Then unauthorized response is received

  Scenario: Update default status
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0000",
        "code": "in progress",
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
    When I request "/api/v1/EN/workflow/default/status/@workflow_status@" using HTTP PUT
    Then the response code is 200

  Scenario: Update default status (not authorized)
    When I request "/api/v1/EN/workflow/default/status/@workflow_status@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@workflow_status@" using HTTP GET
    Then the response code is 200

  Scenario: Get default status (not authorized)
    When I request "/api/v1/EN/workflow/default/status/@workflow_status@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@workflow_status@" using HTTP DELETE
    Then the response code is 200

  Scenario: Delete default status (not authorized)
    When I request "/api/v1/EN/workflow/default/status/@workflow_status@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete default status (not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Get default statuses
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/status" using HTTP GET
    Then grid response is received

  Scenario: Get default statuses (not authorized)
    When I request "/api/v1/EN/workflow/default/status" using HTTP GET
    Then unauthorized response is received

  # TODO Check create workflow action with all incorrect possibilities
  # TODO Check update workflow action with all incorrect possibilities
