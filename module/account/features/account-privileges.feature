Feature: Account roles

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
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role"

  Scenario: Create user
    Given remember param "user_email" with value "@@random_uuid@@@ergonode.com"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@user_email@",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@",
          "isActive": true
      }
      """
    Then the response status code should be 201
    And store response param "id" as "user"

  Scenario: Create text attribute
    Given remember param "attribute_code" with value "TEXT_@@random_code@@"
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Delete text attribute
    Given I am Authenticated as "@user_email@"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 403
