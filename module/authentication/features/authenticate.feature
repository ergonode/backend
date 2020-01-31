Feature: Authentication module

  Scenario: Create default role
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Default role for user (@@random_uuid@@)",
         "description": "Default role for user",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received
    And remember response param "id" as "inactive_user_role"

  Scenario: Create inactive user
    Given remember param "inactive_username" with value "@@random_uuid@@@ergonode.com"
    Given remember param "inactive_password" with value "12345678"
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then created response is received

  Scenario: Authenticate with incorrect credentials (active user)
    Given the request body is:
      """
      {
        "username": "not-existing-email@ergonode.com",
        "password": "111"
      }
      """
    When I request "/api/v1/login" using HTTP POST
    Then unauthorized response is received

  Scenario: Authenticate with correct credentials (active user)
    Given the request body is:
      """
      {
        "username": "@@default_user_username@@",
        "password": "@@default_user_password@@"
      }
      """
    When I request "/api/v1/login" using HTTP POST
    Then the response code is 200

  Scenario: Authenticate without credentials (active user)
    When I request "/api/v1/login" using HTTP POST
    Then unauthorized response is received

  Scenario: Authenticate with correct credentials (inactive user)
    Given the request body is:
      """
      {
        "username": "@inactive_username@",
        "password": "@inactive_password@"
      }
      """
    When I request "/api/v1/login" using HTTP POST
    Then unauthorized response is received
