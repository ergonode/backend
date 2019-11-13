Feature: Account module

  Scenario: Get profile
    Given current authentication token
    When I request "/api/v1/profile" using HTTP GET
    Then the response code is 200

  Scenario: Get profile (not authorized)
    When I request "/api/v1/profile" using HTTP GET
    Then unauthorized response is received

  @changePassword
  Scenario: Create role
    Given current authentication token
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

  Scenario: Create role (without name)
    Given current authentication token
    Given the request body is:
      """
      {
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then validation error response is received

  Scenario: Create role (without description)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then validation error response is received

  Scenario: Create role (without privileges)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role"
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received

  Scenario: Create role (wrong parameter - name)
    Given current authentication token
    Given the request body is:
      """
      {
         "test": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then validation error response is received

  Scenario: Create role (empty name)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then validation error response is received

  Scenario: Create role (empty description)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then validation error response is received

  Scenario: Create role (empty privileges)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": []
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received

  Scenario: Create role (no existing privileges)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["test", "test2"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then validation error response is received

  Scenario: Create role for delete
    Given current authentication token
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
    Given current authentication token
    When I request "/api/v1/EN/roles/@role_to_delete@" using HTTP DELETE
    Then empty response is received
    And delete remembered "role_to_delete"

  Scenario: Delete role (not found)
    Given current authentication token
    When I request "/api/v1/EN/roles/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Update role
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role 2 (@@random_uuid@@)",
         "description": "Test role 2",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then empty response is received

  Scenario: Update role (not authorized)
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update role (not found)
    Given current authentication token
    When I request "/api/v1/EN/roles/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update role (without name)
    Given current authentication token
    Given the request body is:
      """
      {
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then validation error response is received

  Scenario: Update role (without description)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then validation error response is received

  Scenario: Update role (without privileges)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role"
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then empty response is received

  Scenario: Update role (wrong parameter - name)
    Given current authentication token
    Given the request body is:
      """
      {
         "test": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then validation error response is received

  Scenario: Update role (empty name)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then validation error response is received

  Scenario: Update role (empty description)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then validation error response is received

  Scenario: Update role (empty privilages)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": []
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then empty response is received

  Scenario: Update role (no existing privilages)
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["test", "test2"]
      }
      """
    When I request "/api/v1/EN/roles/@role@" using HTTP PUT
    Then validation error response is received

  Scenario: Get role
    Given current authentication token
    When I request "/api/v1/EN/roles/@role@" using HTTP GET
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Get role (not authorized)
    When I request "/api/v1/EN/roles/@role@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get role (not found)
    Given current authentication token
    When I request "/api/v1/EN/roles/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get roles
    Given current authentication token
    When I request "/api/v1/EN/roles" using HTTP GET
    Then grid response is received

  Scenario: Get roles (not authorized)
    When I request "/api/v1/EN/roles" using HTTP GET
    Then unauthorized response is received

  Scenario: Get roles (order by name)
    Given current authentication token
    When I request "/api/v1/EN/roles?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get roles (order by description)
    Given current authentication token
    When I request "/api/v1/EN/roles?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get roles (order by users_count)
    Given current authentication token
    When I request "/api/v1/EN/roles?field=users_count" using HTTP GET
    Then grid response is received

  Scenario: Get roles (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/roles?limit=25&offset=0&filter=name%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get roles (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/roles?limit=25&offset=0&filter=description%3DManage" using HTTP GET
    Then grid response is received

  Scenario: Get roles (filter by user_count)
    Given current authentication token
    When I request "/api/v1/EN/roles?limit=25&offset=0&filter=users_count%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get roles (not authorized)
    When I request "/api/v1/EN/roles" using HTTP GET
    Then unauthorized response is received

  @changePassword
  Scenario: Create user
    Given current authentication token
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

  Scenario: Create user (no email)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty email)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (no firsName)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty firsName)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (no lastName)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty lastName)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (no language)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty language)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (no password)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty password)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": "",
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (no passwordRepeat)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty passwordRepeat)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": "",
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (no roleId)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (empty roleId)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": ""
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (not UUID roleID)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "test"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Create user (random UUID roleID)
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@@random_uuid@@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then validation error response is received

  Scenario: Delete role (with conflict)
    Given current authentication token
    When I request "/api/v1/EN/roles/@role@" using HTTP DELETE
    Then the response code is 409
    And the JSON object contains keys "code,message"

  Scenario: Update user
    Given current authentication token
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
    Then empty response is received

  Scenario: Update user (not authorized)
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update user (not found)
    Given current authentication token
    When I request "/api/v1/EN/accounts/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update user (no firsName)
    Given current authentication token
    Given the request body is:
      """
      {
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (empty firsName)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (no lastName)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (empty lastName)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (no language)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (empty language)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (no password)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (empty password)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": "",
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (no passwordRepeat)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (empty passwordRepeat)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": "",
          "roleId": "@role@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (no roleId)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (empty roleId)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": ""
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (not UUID roleID)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "test"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Update user (random UUID roleID)
    Given current authentication token
    Given the request body is:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@@random_uuid@@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@" using HTTP PUT
    Then validation error response is received

  Scenario: Get user
    Given current authentication token
    When I request "/api/v1/EN/accounts/@user@" using HTTP GET
    Then the response code is 200
    And the JSON object contains keys "id"

  Scenario: Get user (not authorized)
    When I request "/api/v1/EN/accounts/@user@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get user (not found)
    Given current authentication token
    When I request "/api/v1/EN/accounts/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get users
    Given current authentication token
    When I request "/api/v1/EN/accounts" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by email)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=email" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by first_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=first_name" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by last_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=last_name" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by language)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=language" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by role_id)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=role_id" using HTTP GET
    Then grid response is received

  Scenario: Get users (order by is_active)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=is_active" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by email)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=email%3Dtest" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by first_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=first_name%3DJohn" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by last_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3DBravo" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by language)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=language%3DAR" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by role_id)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=role_id%3D11b3145f-88e0-43b7-8f5c-a474c925622b" using HTTP GET
    Then grid response is received

  Scenario: Get users (filter by is_active)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=is_active%3Dtrue" using HTTP GET
    Then grid response is received

  @changePassword
  Scenario: Change password
    Given current authentication token
    Given the request body is:
      """
      {
          "password": 12345678,
          "password_repeat": 12345678
      }
      """
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then empty response is received

  @changePassword
  Scenario: Change password (recover default password)
    Given current authentication token
    Given the request body is:
      """
      {
          "password": "@@default_user_password@@",
          "password_repeat": "@@default_user_password@@"
      }
      """
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then empty response is received

  @changePassword
  Scenario: Change password (not authorized)
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then unauthorized response is received

  @changePassword
  Scenario: Change password (not found)
    Given current authentication token
    When I request "/api/v1/EN/accounts/@@static_uuid@@/password" using HTTP PUT
    Then not found response is received

  @changePassword
  Scenario: Change password (without data)
    Given current authentication token
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

  @changePassword
  Scenario: Change password (with too short password)
    Given current authentication token
    Given the request body is:
      """
      {
          "password": 123,
          "password_repeat": 123
      }
      """
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

  @changePassword
  Scenario: Change password (with empty repeated password)
    Given current authentication token
    Given the request body is:
      """
      {
          "password": 12345678,
          "password_repeat": ""
      }
      """
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

  @changePassword
  Scenario: Change password (with not identical passwords)
    Given current authentication token
    Given the request body is:
      """
      {
          "password": 12345678,
          "password_repeat": "abc"
      }
      """
    When I request "/api/v1/EN/accounts/@user@/password" using HTTP PUT
    Then validation error response is received

  Scenario: Get privilege dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/privileges" using HTTP GET
    Then the response code is 200

  Scenario: Get privilege dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/privileges" using HTTP GET
    Then unauthorized response is received

  Scenario: Get accounts (order by id)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order by email)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=email" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order by first_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=first_name" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order by last_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=last_name" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order by language)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=language" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order by role_id)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=role_id" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order by is_active)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=is_active" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=email&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/accounts?field=email&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=id%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (filter by email)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=email%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (filter by first_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=first_name%3DCAT" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (filter by last_name)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (filter by language)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=language%3D1" using HTTP GET
    Then grid response is received

#  TODO invalid input syntax for type uuid: "asd1"
#  Scenario: Get accounts (filter by role_id)
#    Given current authentication token
#    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=role_id%3Dasd1" using HTTP GET
#    Then grid response is received

  Scenario: Get accounts (filter by is_active)
    Given current authentication token
    When I request "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3Dasd1" using HTTP GET
    Then grid response is received

  Scenario: Get accounts (not authorized)
    When I request "/api/v1/EN/accounts" using HTTP GET
    Then unauthorized response is received

  # TODO Check user avatar change action with correct and incorrect file
