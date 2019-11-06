Feature: Note module

  Scenario: Create role
    Given current authentication token
    Given the request body is:
      """
      {
         "name": "Test note role (@@random_uuid@@)",
         "description": "Test note role",
         "privileges": ["PRODUCT_CREATE","PRODUCT_UPDATE","PRODUCT_READ","PRODUCT_DELETE"]
      }
      """
    When I request "/api/v1/EN/roles" using HTTP POST
    Then created response is received
    And remember response param "id" as "note_role"
    And remember response param "id" as "object_id"

  Scenario: Create user
    Given current authentication token
    Given the request body is:
      """
      {
          "email": "note_@@random_uuid@@@ergonode.com",
          "firstName": "Note",
          "lastName": "Author",
          "language": "EN",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@note_role@"
      }
      """
    When I request "/api/v1/EN/accounts" using HTTP POST
    Then created response is received
    And remember response param "id" as "note_author"

  Scenario: Create note
    Given current authentication token
    Given the request body is:
      """
      {
          "author_id": "@note_author@",
          "object_id": "@object_id@",
          "content": "Note to object @object_id@ by user @note_random@"
      }
      """
    When I request "/api/v1/EN/notes" using HTTP POST
    Then created response is received
    And remember response param "id" as "note_id"

  Scenario: Get note
    Given current authentication token
    When I request "/api/v1/EN/notes/@note_id@" using HTTP GET
    Then the response code is 200

  Scenario: Request note grid
    Given current authentication token
    When I request "api/v1/EN/notes" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Request note grid for given object_id
    Given current authentication token
    When I request "api/v1/EN/notes?filter=object_id=@object_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /Note to object/
    """

  Scenario: Change note
    Given current authentication token
    Given the request body is:
      """
      {
          "content": "New comment for note to object @object_id@ by user @note_random@"
      }
      """
    When I request "/api/v1/EN/notes/@note_id@" using HTTP PUT
    Then created response is received
    And remember response param "id" as "note_id"

  Scenario: Get note after update
    Given current authentication token
    When I request "/api/v1/EN/notes/@note_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /New comment for note to object/
    """
  Scenario: Delete note grid
    Given current authentication token
    When I request "api/v1/EN/notes/@note_id@" using HTTP DELETE
    Then empty response is received

  Scenario: Get removed note
    Given current authentication token
    When I request "/api/v1/EN/notes/@note_id@" using HTTP GET
    Then not found response is received

  Scenario: Request empty note grid for given object_id
    Given current authentication token
    When I request "api/v1/EN/notes?filter=object_id=@object_id@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 0/
    """
