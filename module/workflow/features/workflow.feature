Feature: Workflow

  Scenario: Create status (for workflow)
    Given current authentication token
    Given the request body is:
      """
      {
        "color": "#ff0",
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

  Scenario: Get status (for workflow)
    Given current authentication token
    When I request "/api/v1/EN/status/@workflow_status@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "workflow_status_code"

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Create status (wrong language parameter)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "color": "#ff0",
#        "code": "ST @@random_md5@@",
#        "name": {
#          "ZZ": "PL",
#          "EN": "EN"
#        },
#        "description": {
#          "PL": "PL",
#          "EN": "EN"
#        }
#      }
#      """
#    When I request "/api/v1/EN/status" using HTTP POST
#    Then validation error response is received

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Create status (wrong language parameter)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "color": "#ff0",
#        "code": "ST @@random_md5@@",
#        "name": {
#          "ZZ": "PL",
#          "EN": "EN"
#        },
#        "description": {
#          "ZZ": "PL",
#          "EN": "EN"
#        }
#      }
#      """
#    When I request "/api/v1/EN/status" using HTTP POST
#    Then created response is received

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Update status (wrong language parameter)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "color": "#ff0",
#        "code": "ST @@random_md5@@",
#        "name": {
#          "ZZ": "PL",
#          "EN": "EN"
#        },
#        "description": {
#          "PL": "PL",
#          "EN": "EN"
#        }
#      }
#      """
#    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
#    Then validation error response is received

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Update status (wrong language parameter)
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "color": "#ff0",
#        "code": "ST @@random_md5@@",
#        "name": {
#          "ZZ": "PL",
#          "EN": "EN"
#        },
#        "description": {
#          "ZZ": "PL",
#          "EN": "EN"
#        }
#      }
#      """
#    When I request "/api/v1/EN/status/@workflow_status@" using HTTP PUT
#    Then created response is received

  Scenario: Create workflow
    Given current authentication token
    Given the request body is:
    """
      {
        "code": "WRK_@@random_code@@",
        "statuses": ["@workflow_status_code@"]
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

  Scenario: Delete workflow (not authorized)
    When I request "/api/v1/EN/workflow/default" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete workflow
    Given current authentication token
    When I request "/api/v1/EN/workflow/default" using HTTP DELETE
    Then empty response is received
