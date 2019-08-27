Feature: Account module

  Scenario: Get profile
    Given Current authentication token
    When I request "/api/v1/profile" using HTTP GET
    Then the response code is 200

  Scenario: Get profile (not authorized)
    When I request "/api/v1/profile" using HTTP GET
    Then unauthorized response is received

  Scenario: Create role
    Given Current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received
    And remember response param "id" as "role"

  Scenario: Create role (not authorized)
    Given I request "/api/v1/EN/roles" using HTTP POST
    Then unauthorized response is received

  Scenario: Create role to delete
    Given Current authentication token
    Given the request body is:
      """
      {
         "name": "Test role to delete (@@uuid@@)",
         "description": "Test role to delete",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received
    And remember response param "id" as "role_to_delete"

  Scenario: Delete role for delete (not authorized)
    When I request "/api/v1/EN/roles/@role_to_delete@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete role for delete
    Given Current authentication token
    When I request "/api/v1/EN/roles/@role_to_delete@" using HTTP DELETE
    Then the response code is 202
    And delete remembered "role_to_delete"

  Scenario: Get role
    Given Current authentication token
    When I request "/api/v1/EN/roles/@role@" using HTTP GET
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Get role (not authorized)
    When I request "/api/v1/EN/roles/@role@" using HTTP GET
    Then unauthorized response is received

  Scenario: Update role
    Given Current authentication token
    Given the request body is:
      """
      {
         "name": "Test role 2 (@@uuid@@)",
         "description": "Test role 2",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then the response code is 201

  Scenario: Update role (not authorized)
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update not existing role
    Given Current authentication token
    When I request "/api/v1/EN/roles/not-existing-role" using HTTP PUT
    Then not found response is received

  Scenario: Get not existing role
    Given Current authentication token
    When I request "/api/v1/EN/roles/not-existing-role" using HTTP GET
    Then not found response is received

  Scenario: Delete not existing role
    Given Current authentication token
    When I request "/api/v1/EN/roles/not-existing-role" using HTTP DELETE
    Then not found response is received

  # TODO Check role create action (all incorrect possibilities)
  # TODO Check role update action (all incorrect possibilities)
  # TODO Check role delete action (conflicted)
  # TODO Check role grid (all possibilities)

  Scenario: Create user
    Given Current authentication token
    Given the request body is:
      """
      {
          "email": "@@uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Given I request "/api/v1/EN/accounts" using HTTP POST
    Then created response is received
    And remember response param "id" as "user"

  Scenario: Create user (not authorized)
    Given I request "/api/v1/EN/accounts" using HTTP POST
    Then unauthorized response is received

  Scenario: Delete role with conflict
    Given Current authentication token
    When I request "/api/v1/EN/roles/@role@" using HTTP DELETE
    Then the response code is 422
    And the JSON object contains keys "code,message"

  Scenario: Get user
    Given Current authentication token
    When I request "/api/v1/EN/accounts/@user@" using HTTP GET
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Get user (not authorized)
    When I request "/api/v1/EN/accounts/@user@" using HTTP GET
    Then unauthorized response is received

  Scenario: Update user
    Given Current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test (changed)",
          "lastName": "Test (changed)",
          "language": "EN",
          "password": 123456789,
          "passwordRepeat": 123456789,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Update user (not authorized)
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update not existing role
    Given Current authentication token
    When I request "/api/v1/EN/accounts/not-existing-user" using HTTP PUT
    Then not found response is received

  Scenario: Get not existing role
    Given Current authentication token
    When I request "/api/v1/EN/accounts/not-existing-user" using HTTP GET
    Then not found response is received

  Scenario: Delete not existing role
    Given Current authentication token
    When I request "/api/v1/EN/accounts/not-existing-user" using HTTP DELETE
    Then not found response is received

#  TODO Something wrong with password change, it change password, but for logged user!
#  Scenario: I change password
#    When I create user
#    And I get user ID
#    And I change user password
#    Then I get 201 result code

#  Scenario: I change password of not existing user
#    When I set not existing user ID
#    And I change user password
#    Then I get 404 result code

  # TODO Check user create action (all incorrect possibilities)
  # TODO Check user update action (all incorrect possibilities)
  # TODO Check user avatar change action (correct, incorrect file)
  # TODO Check user change password action (not identical passwords)
  # TODO Check user login (inactive)
  # TODO Check user grid (all possibilities)

  Scenario: Get privilege dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/privileges" using HTTP GET
    Then the response code is 200

  Scenario: Get privilege dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/privileges" using HTTP GET
    Then unauthorized response is received

  Scenario: Get profile log (order by author)
    Given Current authentication token
    When I request "/api/v1/profile/log?field=author" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (order by recorded_at)
    Given Current authentication token
    When I request "/api/v1/profile/log?field=recorded_at" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (order by event)
    Given Current authentication token
    When I request "/api/v1/profile/log?field=event" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (not authorized)
    When I request "/api/v1/profile/log" using HTTP GET
    Then unauthorized response is received

  # TODO Check profile log (with all filters)

  Scenario: Get accounts log (order by author)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/log?field=author" using HTTP GET
    Then grid response is received

  Scenario: Get accounts log (order by recorded_at)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/log?field=recorded_at" using HTTP GET
    Then grid response is received

  Scenario: Get accounts log (order by event)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/log?field=event" using HTTP GET
    Then grid response is received

  Scenario: Get accounts log (not authorized)
    When I request "/api/v1/EN/accounts/log" using HTTP GET
    Then unauthorized response is received

  # TODO Check accounts log (with all filters)
