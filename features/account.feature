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
         "name": "Test role (@@random_uuid@@)",
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

  Scenario: Create role for delete
    Given Current authentication token
    Given the request body is:
      """
      {
         "name": "Test role to delete (@@random_uuid@@)",
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

  Scenario: Delete role (not found)
    Given Current authentication token
    When I request "/api/v1/EN/roles/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Update role
    Given Current authentication token
    Given the request body is:
      """
      {
         "name": "Test role 2 (@@random_uuid@@)",
         "description": "Test role 2",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then the response code is 201

  Scenario: Update role (not authorized)
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update role (not found)
    Given Current authentication token
    When I request "/api/v1/EN/roles/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get role
    Given Current authentication token
    When I request "/api/v1/EN/roles/@role@" using HTTP GET
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Get role (not authorized)
    When I request "/api/v1/EN/roles/@role@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get role (not found)
    Given Current authentication token
    When I request "/api/v1/EN/roles/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get roles
    Given Current authentication token
    When I request "/api/v1/EN/roles" using HTTP GET
    Then grid response is received

  Scenario: Get roles (order by name)
    Given Current authentication token
    When I request "/api/v1/EN/roles?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get roles (order by description)
    Given Current authentication token
    When I request "/api/v1/EN/roles?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get roles (order by users_count)
    Given Current authentication token
    When I request "/api/v1/EN/roles?field=users_count" using HTTP GET
    Then grid response is received

  Scenario: Get roles (filter by name)
    Given Current authentication token
    When I request "/api/v1/EN/roles?limit=25&offset=0&filter=name%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get roles (filter by description)
    Given Current authentication token
    When I request "/api/v1/EN/roles?limit=25&offset=0&filter=description%3DManage" using HTTP GET
    Then grid response is received

  Scenario: Get roles (filter by user_count)
    Given Current authentication token
    When I request "/api/v1/EN/roles?limit=25&offset=0&filter=users_count%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get roles (not authorized)
    When I request "/api/v1/EN/roles" using HTTP GET
    Then unauthorized response is received

  Scenario: Create user
    Given Current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then created response is received
    And remember response param "id" as "user"

  Scenario: Create user (not authorized)
    Given I request "/api/v1/EN/accounts" using HTTP POST
    Then unauthorized response is received

  Scenario: Delete role (with conflict)
    Given Current authentication token
    When I request "/api/v1/EN/roles/@role@" using HTTP DELETE
    Then the response code is 422
    And the JSON object contains keys "code,message"

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

  Scenario: Update user (not found)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get user
    Given Current authentication token
    When I request "/api/v1/EN/accounts/@user@" using HTTP GET
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Get user (not authorized)
    When I request "/api/v1/EN/accounts/@user@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get user (not found)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get users
    Given Current authentication token
    When I request "/api/v1/EN/accounts" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by email)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?field=email" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by first_name)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?field=first_name" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by last_name)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?field=last_name" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by language)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?field=language" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by role_id)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?field=role_id" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by is_active)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?field=is_active" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by email)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=email%3Dtest" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by first_name)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=first_name%3DJohn" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by last_name)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3DBravo" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by language)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=language%3DAR" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by role_id)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=role_id%3D11b3145f-88e0-43b7-8f5c-a474c925622b" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by is_active)
    Given Current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=is_active%3Dtrue" using HTTP GET
    Then grid response is received

#  TODO Something wrong with password change, it change password, but for logged user!
  Scenario: Change password
    Given Current authentication token
    Given the following form parameters are set:
      | password        | 12345678 |
      | password_repeat | 12345678 |
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then created response is received

  Scenario: Change password (not authorized)
    Given the following form parameters are set:
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then unauthorized response is received

  Scenario: Change password (not found)
    Given Current authentication token
    Given the following form parameters are set:
    When I request "/api/v1/EN/accounts/@@static_uuid@@/password" using HTTP PUT
    Then not found response is received

  Scenario: Change password (without data)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

  Scenario: Change password (with incorrect data)
    Given Current authentication token
    Given the following form parameters are set:
      | password        | 123 |
      | password_repeat | 123 |
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

  Scenario: Change password (with not identical passwords)
    Given Current authentication token
    Given the following form parameters are set:
      | password        | 12345678 |
      | password_repeat | 12345786 |
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

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

  Scenario: Get profile log (filter by time)
    Given Current authentication token
    When I request "/api/v1/profile/log?limit=25&offset=0&filter=recorded_at%3D2019" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (filter by author)
    Given Current authentication token
    When I request "/api/v1/profile/log?limit=25&offset=0&filter=author%3DSystem" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (not authorized)
    When I request "/api/v1/profile/log" using HTTP GET
    Then unauthorized response is received

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

  Scenario: Get accounts log (filter by time)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at%3D2019" using HTTP GET
    Then grid response is received

  Scenario: Get accounts log (filter by author)
    Given Current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=author%3DSystem" using HTTP GET
    Then grid response is received

  Scenario: Get accounts log (not authorized)
    When I request "/api/v1/EN/accounts/log" using HTTP GET
    Then unauthorized response is received

  # TODO Check role create action with all incorrect possibilities
  # TODO Check role update action with all incorrect possibilities
  # TODO Check user create action with all incorrect possibilities
  # TODO Check user update action with all incorrect possibilities
  # TODO Check user avatar change action with correct and incorrect file
