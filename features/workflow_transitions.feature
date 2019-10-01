Feature: Workflow

  Scenario: Create source status
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
    And remember response param "id" as "workflow_source_status"

  Scenario: Create destination status
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
    And remember response param "id" as "workflow_destination_status"

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_source_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_source_status_code"

  Scenario: Get default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_destination_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_destination_status_code"

  Scenario: Add transition to Workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "source": "@workflow_source_status_code@",
        "destination": "@workflow_destination_status_code@",
        "name": {
         "PL": "Translated name PL",
         "EN": "Translated name EN"
        },
        "description": {
         "PL": "Translated description PL",
         "EN": "Translated description EN"
        }
      }
    """
    When I request "/api/v1/EN/workflow/default/transitions" using HTTP POST
    Then the response code is 201

  Scenario: Get transition in default Workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response code is 200

  Scenario: Delete transition in default Workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@" using HTTP DELETE
    Then empty response is received

  Scenario: Get transition in default Workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then not found response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_source_status_code@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete default status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_destination_status_code@" using HTTP DELETE
    Then empty response is received
