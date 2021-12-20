Feature: Workflow Condition

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get condition list
    When I send a GET request to "/api/v1/en_GB/workflow/condition/dictionary"
    Then the response status code should be 200

  Scenario: Get condition (not found)
    When I send a GET request to "/api/v1/en_GB/workflow/condition/ASD"
    Then the response status code should be 404

  Scenario Outline: Get condition configuration (<type>)
    When I send a GET request to "/api/v1/en_GB/workflow/condition/<type>"
    Then the response status code should be 200
    And the JSON node "type" should contain "<type>"
    And the JSON node "name" should exist
    And the JSON node "phrase" should exist
    Examples:
      | type                           |
      | ATTRIBUTE_EXISTS_CONDITION     |
      | PRODUCT_COMPLETENESS_CONDITION |
      | ROLE_IS_CONDITION              |
      | USER_IS_CONDITION              |

  Scenario: Create from status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "FROM @@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "status_from_id"

  Scenario: Create to status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "TO @@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "status_to_id"

  Scenario: Add transition to workflow
    When I send a POST request to "/api/v1/en_GB/workflow/default/transitions" with body:
      """
      {
        "from": "@status_from_id@",
        "to": "@status_to_id@"
      }
      """
    Then the response status code should be 201

  Scenario: Update empty transition conditions to workflow
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "NOT_EXIST_TYPE"
          }
        ]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.conditions.element-0.type[0]" should exist