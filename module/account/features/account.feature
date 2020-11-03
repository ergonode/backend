Feature: Account module

  Scenario: Get profile
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 200
    And the JSON node id should exist
    And the JSON node first_name should exist
    And the JSON node last_name should exist
    And the JSON node email should exist
    And the JSON node language should exist
    And the JSON node avatar_filename should exist
    And the JSON node role should exist
    And the JSON node privileges should exist
    And the JSON node language_privileges should exist
    And the JSON node language_privileges.en_GB should exist

  Scenario: Get profile (not authorized)
    When I send a GET request to "/api/v1/profile"
    Then the response status code should be 401

  @changePassword
  Scenario: Create role 1
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And remember param "role_name_1" with value "Test role (@@random_uuid@@)"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "@role_name_1@",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role_1"

  Scenario: Create role 2
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And remember param "role_name_2" with value "Test role (@@random_uuid@@)"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "@role_name_2@",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role_2"

  Scenario: Create role (with the same name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "@role_name_1@",
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role (not authorized)
    Given I send a POST request to "/api/v1/en_GB/roles"
    Then the response status code should be 401

  Scenario: Create role (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "description": "Test role",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Create role with long name
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test",
         "description": "Test test test test ",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400
    And the JSON node errors.name should exist

  Scenario: Create role with long description
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test",
         "description": "Test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400
    And the JSON node errors.description should exist

  Scenario: Create role (without description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201

  Scenario: Create role (without privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
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
    When I send a POST request to "/api/v1/en_GB/roles" with body:
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
    When I send a POST request to "/api/v1/en_GB/roles" with body:
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
    When I send a POST request to "/api/v1/en_GB/roles" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201

  Scenario: Create role (empty privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/roles" with body:
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
    When I send a POST request to "/api/v1/en_GB/roles" with body:
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
    When I send a POST request to "/api/v1/en_GB/roles" with body:
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
    When I send a DELETE request to "/api/v1/en_GB/roles/@role_to_delete@"
    Then the response status code should be 401

  Scenario: Delete role for delete
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/roles/@role_to_delete@"
    Then the response status code should be 204
    And delete remembered "role_to_delete"

  Scenario: Delete role (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/roles/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update role (with the same name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
      """
      {
         "name": "@role_name_1@",
         "description": "Test role 2",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 204

  Scenario: Update role (with existing name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
      """
      {
         "name": "@role_name_2@",
         "description": "Test role 2",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 400

  Scenario: Update role (not authorized)
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@"
    Then the response status code should be 401

  Scenario: Update role (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update role (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
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
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 204

  Scenario: Update role (without privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
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
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
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
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
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
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "",
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 204

  Scenario: Update role (empty privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
      """
      {
         "name": "Test role (@@random_uuid@@)",
         "description": "Test role",
         "privileges": []
      }
      """
    Then the response status code should be 204

  Scenario: Update role (no existing privileges)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/roles/@role_1@" with body:
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
    When I send a GET request to "/api/v1/en_GB/roles/@role_1@"
    Then the response status code should be 200
    And the JSON node "id" should exist

  Scenario: Get role (not authorized)
    When I send a GET request to "/api/v1/en_GB/roles/@role_1@"
    Then the response status code should be 401

  Scenario: Get role (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get roles
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (not authorized)
    When I send a GET request to "/api/v1/en_GB/roles"
    Then the response status code should be 401

  Scenario: Get roles (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles?field=name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles?field=description"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (order by users_count)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles?field=users_count"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles?limit=25&offset=0&filter=name%3Dsuper"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (filter by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles?limit=25&offset=0&filter=description%3DManage"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (filter by user_count)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/roles?limit=25&offset=0&filter=users_count%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get roles (not authorized)
    When I send a GET request to "/api/v1/en_GB/roles"
    Then the response status code should be 401

  @changePassword
  Scenario: Create user
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "user"

  Scenario: Create user (not authorized)
    Given I send a POST request to "/api/v1/en_GB/accounts"
    Then the response status code should be 401

  Scenario: Create user (no email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (wrong email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "noemail",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": "",
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": "",
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (no roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678
      }
      """
    Then the response status code should be 400

  Scenario: Create user (empty roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
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
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
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
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create user (long mail)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@-tjtkh5m23vwqav5lwfeopipdf77e7an30ntsfl72zqeqrs3jqd0ikdsalq3m6cmj32a8v0zsk1hm1mw9mxeizc85q6p4p5141xy2oqfdysda6335bb7bbsifvxnxa693q932vxxnpzdu1oyx0cqzzsqqgzxw9i2iq1y0mwz46889pvovsj72l8j5zcreh4qmhij1mfsy1tsa@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 201

  Scenario: Create user (too long mail)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@-tjtkh5m23vwqav5lwfeopipdf77e7an30ntsfl72zqeqrs3jqd0ikdsalq3m6cmj32a8v0zsk1hm1mw9mxeizc85q6p4p5141xy2oqfdysda6335bb7bbsifvxnxa693q932vxxnpzdu1oyx0cqzzsqqgzxw9i2iq1y0mwz46889pvovsj72l8j5zcreh4qmhij1mfsy1tsa2@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400


  Scenario: Delete role (with conflict)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/roles/@role_1@"
    Then the response status code should be 409
    And the JSON node "code" should exist
    And the JSON node "message" should exist

  Scenario: Update user
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test (changed)",
          "lastName": "Test (changed)",
          "language": "en_GB",
          "password": 123456789,
          "passwordRepeat": 123456789,
          "roleId": "@role_1@",
          "languagePrivilegesCollection": {
             "en_GB": {
               "read": true,
               "edit": true
             }
           }
      }
      """
    Then the response status code should be 204

  Scenario: Update user (not authorized)
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@"
    Then the response status code should be 401

  Scenario: Update user (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update user (no firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty firsName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (no lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty lastName)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (no language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (no password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": "",
          "passwordRepeat": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (no passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty passwordRepeat)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": "",
          "roleId": "@role_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (no roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678
      }
      """
    Then the response status code should be 400

  Scenario: Update user (empty roleId)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
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
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
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
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario: Update user (no existing language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@",
          "languagePrivilegesCollection": {
             "test": {
               "read": true,
               "edit": true
             }
           }
      }
      """
    Then the response status code should be 400

  Scenario: Update user (field missing in privilege object)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@",
          "languagePrivilegesCollection": {
             "en_GB": {
               "read": true
             }
           }
      }
      """
    Then the response status code should be 204

  Scenario: Update user (empty privilege)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@" with body:
      """
      {
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role_1@",
          "languagePrivilegesCollection": {
             "en_GB": {
             }
           }
      }
      """
    Then the response status code should be 204

  Scenario: Get user
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts/@user@"
    Then the response status code should be 200
    And the JSON node "id" should exist

  Scenario: Get user (not authorized)
    When I send a GET request to "/api/v1/en_GB/accounts/@user@"
    Then the response status code should be 401

  Scenario: Get user (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get users
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (order by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=email"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (order by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=first_name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (order by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=last_name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (order by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=language"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (order by role_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=role_id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (order by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=is_active"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (filter by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=email%3Dtest"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (filter by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=first_name%3DJohn"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (filter by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=last_name%3DBravo"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (filter by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=language%3DAR"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (filter by role_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=role_id%3D11b3145f-88e0-43b7-8f5c-a474c925622b"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get users (filter by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=is_active%3Dtrue"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  @changePassword
  Scenario: Change password
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password" with body:
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
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password" with body:
      """
      {
          "password": "@@default_user_password@@",
          "password_repeat": "@@default_user_password@@"
      }
      """
    Then the response status code should be 204

  @changePassword
  Scenario: Change password (not authorized)
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password"
    Then the response status code should be 401

  @changePassword
  Scenario: Change password (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@@static_uuid@@/password"
    Then the response status code should be 404

  @changePassword
  Scenario: Change password (without data)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password"
    Then the response status code should be 400

  @changePassword
  Scenario: Change password (with too short password)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password" with body:
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
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password" with body:
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
    When I send a PUT request to "/api/v1/en_GB/accounts/@user@/password" with body:
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
    When I send a GET request to "/api/v1/en_GB/dictionary/privileges"
    Then the response status code should be 200

  Scenario: Get privilege dictionary (not authorized)
    When I send a GET request to "/api/v1/en_GB/dictionary/privileges"
    Then the response status code should be 401

  Scenario: Get accounts (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=email"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=first_name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=last_name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=language"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order by role_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=role_id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=is_active"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=email&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?field=email&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (filter by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=id%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (filter by email)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=email%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (filter by first_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=first_name%3DCAT"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (filter by last_name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=last_name%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (filter by language)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=language%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

#  TODO invalid input syntax for type uuid: "asd1"
#  Scenario: Get accounts (filter by role_id)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=role_id%3Dasd1"
#    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (filter by is_active)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/accounts?limit=25&offset=0&filter=last_name%3Dasd1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get accounts (not authorized)
    When I send a GET request to "/api/v1/en_GB/accounts"
    Then the response status code should be 401
