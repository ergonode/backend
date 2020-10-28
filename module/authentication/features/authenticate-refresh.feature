Feature: Authentication module - refresh token

  Scenario: Create default role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Default role for user (@@random_uuid@@)",
         "description": "Default role for user",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "active_user_role"

  Scenario: Create active user
    Given remember param "active_username" with value "@@random_uuid@@@ergonode.com"
    Given remember param "active_password" with value "123456789"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
    """
          {
              "email": "@active_username@",
              "firstName": "IS",
              "lastName": "Active",
              "language": "en_GB",
              "password": "@active_password@",
              "passwordRepeat": "@active_password@",
              "isActive": "true",
              "roleId": "@active_user_role@"
          }
          """
    Then the response status code should be 201

  Scenario: Authenticate with correct credentials (active user)
    Given I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/login" with body:
    """
          {
            "username": "@active_username@",
            "password": "@active_password@"
          }
          """
    Then the response status code should be 200
    And store response param "token" as "token"
    And store response param "refresh_token" as "refresh_token"

  Scenario: Get profile
    Given I use Authenticated token "@token@"
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | email | @active_username@ |

  Scenario: Get new token
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/token/refresh" with body:
    """
          {
            "refresh_token": "@refresh_token@"
          }
    """
    Then the response status code should be 200
    And store response param "token" as "new_token"

  Scenario: Get profile
    Given I use Authenticated token "@new_token@"
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | email | @active_username@ |
