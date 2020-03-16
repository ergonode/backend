Feature: Account module

  Scenario: Get profile
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 200

  Scenario: Get profile (not authorized)
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 401

  @changePassword
  Scenario: Create role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role"

  Scenario: Create role (not authorized)
    Given I send a POST request to "/api/v1/EN/roles"
    Then the response status code should be 401

  Scenario: Create role (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role (without description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role (without privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role"
      }
      """
    Then the response status code should be 201

  Scenario: Create role (wrong parameter - name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "test": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role (empty name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role (empty description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role (empty privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": []
      }
      """
    Then the response status code should be 201

  Scenario: Create role (no existing privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["test", "test2"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role for delete
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/roles" with body:
      """
      {
         "name": "Test role to delete (@@random_uuid@@)",
         "description": "Test role to delete",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role_to_delete"

  Scenario: Delete role for delete (not authorized)
    When I send a DELETE request to "/api/v1/EN/roles/@role_to_delete@"
    Then the response status code should be 401

  Scenario: Delete role for delete
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/roles/@role_to_delete@"
    Then the response status code should be 204
    And delete remembered "role_to_delete"

  Scenario: Delete role (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/roles/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "Test role 2 (@@random_uuid@@)",
         "description": "Test role 2",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 204

  Scenario: Update role (not authorized)
    When I send a PUT request to "/api/v1/EN/roles/@role@"
    Then the response status code should be 401

  Scenario: Update role (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update role (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Update role (without description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Update role (without privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role"
      }
      """
    Then the response status code should be 204

  Scenario: Update role (wrong parameter - name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "test": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Update role (empty name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Update role (empty description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Update role (empty privilages)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": []
      }
      """
    Then the response status code should be 204

  Scenario: Update role (no existing privilages)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/roles/@role@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": ["test", "test2"]
      }
      """
    Then the response status code should be 400

  Scenario: Get role
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles/@role@"
    Then the response status code should be 200
    And the JSON node "id" should exist

  Scenario: Get role (not authorized)
    When I send a GET request to "/api/v1/EN/roles/@role@"
    Then the response status code should be 401

  Scenario: Get role (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get roles
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (not authorized)
    When I send a GET request to "/api/v1/EN/roles"
    Then the response status code should be 401

  Scenario: Get roles (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (order by users_count)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles?field=users_count"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles?limit=25&offset=0&filter=name%3Dsuper"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (filter by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles?limit=25&offset=0&filter=description%3DManage"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (filter by user_count)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/roles?limit=25&offset=0&filter=users_count%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get roles (not authorized)
    When I send a GET request to "/api/v1/EN/roles"
    Then the response status code should be 401

  @changePassword
  Scenario: Create user
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 201
    And store response param "id" as "user"

  Scenario: Create user (not authorized)
    Given I send a POST request to "/api/v1/EN/accounts"
    Then the response status code should be 401

  Scenario: Create user (no email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (wrong email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
      """
      {
          "email": "noemail",
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (no lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (no language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (no password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (no passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (no roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (empty roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (not UUID roleID)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Create user (random UUID roleID)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/accounts" with body:
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
    Then the response status code should be 400

  Scenario: Delete role (with conflict)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/roles/@role@"
    Then the response status code should be 409
    And the JSON node "code" should exist
    And the JSON node "message" should exist

  Scenario: Update user
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 204

  Scenario: Update user (not authorized)
    When I send a PUT request to "/api/v1/EN/accounts/@user@"
    Then the response status code should be 401

  Scenario: Update user (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update user (no firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
      """
      {
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (no lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (no language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (no password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (no passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (no roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (not UUID roleID)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Update user (random UUID roleID)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@" with body:
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
    Then the response status code should be 400

  Scenario: Get user
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/@user@"
    Then the response status code should be 200
    And the JSON node "id" should exist

  Scenario: Get user (not authorized)
    When I send a GET request to "/api/v1/EN/accounts/@user@"
    Then the response status code should be 401

  Scenario: Get user (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get users
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (order by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=email"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (order by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=first_name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (order by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=last_name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (order by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=language"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (order by role_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=role_id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (order by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=is_active"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (filter by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=email%3Dtest"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (filter by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=first_name%3DJohn"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (filter by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3DBravo"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (filter by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=language%3DAR"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (filter by role_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=role_id%3D11b3145f-88e0-43b7-8f5c-a474c925622b"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get users (filter by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=is_active%3Dtrue"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  @changePassword
  Scenario: Change password
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password" with body:
      """
      {
          "password": 12345678,
          "password_repeat": 12345678
      }
      """
    Then the response status code should be 204

  @changePassword
  Scenario: Change password (recover default password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password" with body:
      """
      {
          "password": "@@default_user_password@@",
          "password_repeat": "@@default_user_password@@"
      }
      """
    Then the response status code should be 204

  @changePassword
  Scenario: Change password (not authorized)
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password"
    Then the response status code should be 401

  @changePassword
  Scenario: Change password (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@@static_uuid@@/password"
    Then the response status code should be 404

  @changePassword
  Scenario: Change password (without data)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password"
    Then the response status code should be 400

  @changePassword
  Scenario: Change password (with too short password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password" with body:
      """
      {
          "password": 123,
          "password_repeat": 123
      }
      """
    Then the response status code should be 400

  @changePassword
  Scenario: Change password (with empty repeated password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password" with body:
      """
      {
          "password": 12345678,
          "password_repeat": ""
      }
      """
    Then the response status code should be 400

  @changePassword
  Scenario: Change password (with not identical passwords)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/accounts/@user@/password" with body:
      """
      {
          "password": 12345678,
          "password_repeat": "abc"
      }
      """
    Then the response status code should be 400

  Scenario: Get privilege dictionary
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/dictionary/privileges"
    Then the response status code should be 200

  Scenario: Get privilege dictionary (not authorized)
    When I send a GET request to "/api/v1/EN/dictionary/privileges"
    Then the response status code should be 401

  Scenario: Get accounts (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=email"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=first_name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=last_name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=language"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order by role_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=role_id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=is_active"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=email&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?field=email&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (filter by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=id%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (filter by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=email%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (filter by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=first_name%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (filter by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (filter by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=language%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

#  TODO invalid input syntax for type uuid: "asd1"
#  Scenario: Get accounts (filter by role_id)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=role_id%3Dasd1"
#    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (filter by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/accounts?limit=25&offset=0&filter=last_name%3Dasd1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get accounts (not authorized)
    When I send a GET request to "/api/v1/EN/accounts"
    Then the response status code should be 401

  # TODO Check user avatar change action with correct and incorrect file
