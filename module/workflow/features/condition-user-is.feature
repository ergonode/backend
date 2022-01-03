Feature: Workflow Condition user is

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create role
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role_id"

  Scenario: Create user
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "user_id"

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

  Scenario: Update incorrect condition
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "USER_IS_CONDITION"
          }
        ]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.conditions.element-0.user[0]" should exist

  Scenario: Update incorrect condition (user not uuid)
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "USER_IS_CONDITION",
            "user" :"nor uuid"
          }
        ]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.conditions.element-0.user[0]" should exist

  Scenario: Update incorrect condition (user not uuid)
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "USER_IS_CONDITION",
            "user" :"@@random_uuid@@"
          }
        ]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.conditions.element-0.user[0]" should exist

  Scenario: Update empty transition conditions to workflow
    When I send a PUT request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions" with body:
      """
      {
        "conditions": [
          {
            "type": "USER_IS_CONDITION",
            "user": "@user_id@"
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get conditions from transition in workflow
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions/@status_from_id@/@status_to_id@/conditions"
    Then the response status code should be 200
    And the JSON node "[0].type" should contain "USER_IS_CONDITION"