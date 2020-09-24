Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: Create workflow status <id>
    And I send a "POST" request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<id>"
    Examples:
      | id                   |
      | workflow_status_1_id |
      | workflow_status_2_id |

  Scenario: Get workflow types
    When I send a "GET" request to "/api/v1/en_GB/dictionary/workflow-type"
    Then the response status code should be 200

  Scenario: Create workflow
    And I send a "POST" request to "/api/v1/en_GB/workflow" with body:
    """
      {
        "code": "WRK_@@random_code@@",
        "type": "default",
        "statuses": ["@workflow_status_1_id@"]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "workflow_id"

  Scenario: Create workflow (wrong statuses)
    And I send a "POST" request to "/api/v1/en_GB/workflow" with body:
    """
    {
      "code": "WRK_@@random_code@@",
      "type": "default",
      "statuses": ["test"],
      "transitions": []
    }
    """
    Then the response status code should be 400

  Scenario: Get default workflow
    And I send a "GET" request to "/api/v1/en_GB/workflow/default"
    Then the response status code should be 200

  Scenario: Update workflow
    And I send a "PUT" request to "/api/v1/en_GB/workflow/default" with body:
    """
    {
      "statuses": ["@workflow_status_2_id@"]
    }
    """
    Then the response status code should be 201

  Scenario: Delete workflow
    And I send a "DELETE" request to "/api/v1/en_GB/workflow/default"
    Then the response status code should be 204
