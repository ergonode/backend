Feature: Workflow Condition product attribute

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "SELECT@@random_code@@",
          "type": "SELECT",
          "scope": "local"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

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

  Scenario: Update empty transition conditions to workflow
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "ATTRIBUTE_EXISTS_CONDITION",
            "attribute": "invalid option"
          }
        ]
      }
      """

    Then the response status code should be 400
    And the JSON node "errors.conditions.element-0.attribute[0]" should exist

  Scenario: Update empty transition conditions to workflow
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "ATTRIBUTE_EXISTS_CONDITION",
            "attribute": "@attribute_id@"
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get conditions from transition in workflow
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions"
    Then the response status code should be 200
    And the JSON node "[0].type" should contain "ATTRIBUTE_EXISTS_CONDITION"