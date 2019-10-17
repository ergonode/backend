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

  Scenario: Get source status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_source_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_source_status_code"

  Scenario: Get destination status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_destination_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_destination_status_code"

  Scenario: Get status (not authorized)
    When I request "/api/v1/EN/status/@workflow_source_status@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@random_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Add transition to workflow
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
    Then created response is received

  Scenario: Add transition to workflow (duplicated)
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
    Then validation error response is received

  Scenario: Add transition to workflow (without source)
    Given current authentication token
    Given the request body is:
      """
      {
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
    Then validation error response is received

  Scenario: Add transition to workflow (without destination)
    Given current authentication token
    Given the request body is:
      """
      {
        "source": "@workflow_source_status_code@",
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
    Then validation error response is received

  Scenario: Add transition to workflow (source not found)
    Given current authentication token
    Given the request body is:
      """
      {
        "source": "@@random_uuid@@",
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
    Then validation error response is received

  Scenario: Add transition to workflow (destination not found)
    Given current authentication token
    Given the request body is:
      """
      {
        "source": "@workflow_source_status_code@",
        "destination": "@@random_uuid@@",
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
    Then validation error response is received

  Scenario: Add transition to workflow (not authorized)
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
    Then unauthorized response is received

  Scenario: Get transition in default workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then the response code is 200

  Scenario: Get transition in default workflow (not authorized)
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@"
    Then unauthorized response is received

  Scenario: Get transition in default workflow (source not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@@random_uuid@@/@workflow_destination_status_code@"
    Then not found response is received

  Scenario: Get transition in default workflow (destination not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@@random_uuid@@"
    Then not found response is received

  Scenario: Get transitions (order by source)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions?field=source" using HTTP GET
    Then grid response is received

  Scenario: Get transitions (order by destination)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions?field=destination" using HTTP GET
    Then grid response is received

  Scenario: Get transitions (order by name)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get transitions (order by description)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get transitions (not authorized)
    When I request "/api/v1/EN/workflow/default/transitions?field=source" using HTTP GET
    Then unauthorized response is received

  Scenario: Delete transition in default workflow (not authorized)
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete transition in default workflow (source not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@@random_uuid@@/@workflow_destination_status_code@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete transition in default workflow (destination not found)
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@@random_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete transition in default workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default/transitions/@workflow_source_status_code@/@workflow_destination_status_code@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete status (not authorized)
    When I request "/api/v1/EN/status/@workflow_destination_status_code@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete status (not found)
    Given current authentication token
    When I request "/api/v1/EN/status/@@random_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete source status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_source_status_code@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete destination status
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_destination_status_code@" using HTTP DELETE
    Then empty response is received
