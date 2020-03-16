Feature: Authentication module

  Scenario: Create default role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Default role for user (@@random_uuid@@)",
         "description": "Default role for user",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "inactive_user_role"

  Scenario: Create inactive user
    Given remember param "inactive_username" with value "@@random_uuid@@@ergonode.com"
    Given remember param "inactive_password" with value "12345678"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
      """
      {
          "email": "@inactive_username@",
          "firstName": "Not",
          "lastName": "Active",
          "language": "EN",
          "password": "@inactive_password@",
          "passwordRepeat": "@inactive_password@",
          "roleId": "@inactive_user_role@"
      }
      """
    Then the response status code should be 201

  Scenario: Authenticate with incorrect credentials (active user)
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/login" with body:
      """
      {
        "username": "not-existing-email@ergonode.com",
        "password": "111"
      }
      """
    Then the response status code should be 401

  Scenario: Authenticate with correct credentials (active user)
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/login" with body:
      """
      {
        "username": "@@default_user_username@@",
        "password": "@@default_user_password@@"
      }
      """
    Then the response status code should be 200

  Scenario: Authenticate without credentials (active user)
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/login"
    Then the response status code should be 401

  Scenario: Authenticate with correct credentials (inactive user)
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/login" with body:
      """
      {
        "username": "@inactive_username@",
        "password": "@inactive_password@"
      }
      """
    Then the response status code should be 401
