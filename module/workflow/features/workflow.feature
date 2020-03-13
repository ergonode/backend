Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create status (for workflow)
    And I send a "POST" request to "/api/v1/EN/status" with body:
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
    Then the response status code should be 201
    And store response param "id" as "workflow_status"

  Scenario: Get status (for workflow)
    And I send a "GET" request to "/api/v1/EN/status/@workflow_status@"
    Then the response status code should be 200
    And store response param "code" as "workflow_status_code"

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Create status (wrong language parameter)
#    And I send a "POST" request to "/api/v1/EN/attributes" with body:
#    When I send a POST request to "/api/v1/EN/status" with body:
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
#    Then the response status code should be 400

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Create status (wrong language parameter)
#    And I send a "POST" request to "/api/v1/EN/attributes" with body:
#    When I send a POST request to "/api/v1/EN/status" with body:
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
#    Then the response status code should be 201

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Update status (wrong language parameter)
#    And I send a "POST" request to "/api/v1/EN/attributes" with body:
#    When I send a PUT request to "/api/v1/EN/status/@workflow_status@" with body:
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
#    Then the response status code should be 400

#  TODO  problem with language code validation, problem waiting to be fixed
#  Scenario: Update status (wrong language parameter)
#    And I send a "POST" request to "/api/v1/EN/attributes" with body:
#    When I send a PUT request to "/api/v1/EN/status/@workflow_status@" with body:
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
#    Then the response status code should be 201

  Scenario: Create workflow
    And I send a "POST" request to "/api/v1/EN/workflow" with body:
    """
      {
        "code": "WRK_@@random_code@@",
        "statuses": ["@workflow_status_code@"]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "workflow_id"

  Scenario: Create workflow (wrong statuses)
    And I send a "POST" request to "/api/v1/EN/workflow" with body:
    """
    {
      "code": "WRK_@@random_code@@",
      "statuses": ["test"],
      "transitions": []
    }
    """
    Then the response status code should be 400

  Scenario: Get default workflow
    And I send a "GET" request to "/api/v1/EN/workflow/default"
    Then the response status code should be 200

  Scenario: Delete workflow
    And I send a "DELETE" request to "/api/v1/EN/workflow/default"
    Then the response status code should be 204
