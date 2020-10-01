Feature: Account module - avatar

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
         "privileges": ["ATTRIBUTE_CREATE","ATTRIBUTE_UPDATE","ATTRIBUTE_READ","ATTRIBUTE_DELETE"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "role"

  Scenario: Create user
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "user"

  Scenario: Upload avatar image
    When I send a POST request to "/api/v1/en_GB/accounts/@user@/avatar" with params:
      | key    | value                  |
      | upload | @avatar-test-image.jpg |
    Then the response status code should be 204

  Scenario: Upload new avatar image with unsupported extension
    When I send a POST request to "/api/v1/en_GB/accounts/@user@/avatar" with params:
      | key    | value                  |
      | upload | @avatar-test-image.ico |
    Then the response status code should be 400

  Scenario: Upload new avatar image without uploaded file
    When I send a POST request to "/api/v1/en_GB/accounts/@user@/avatar"
    Then the response status code should be 400

  Scenario: Upload new avatar with empty file
    When I send a POST request to "/api/v1/en_GB/accounts/@user@/avatar" with params:
      | key    | value                        |
      | upload | @avatar-test-empty-image.png |
    Then the response status code should be 400

  Scenario: Download uploaded avatar image
    When I send a GET request to "/api/v1/en_GB/accounts/@user@/avatar"
    Then the response status code should be 200
    And the header "content-type" should be equal to "image/png"
    And the header "content-length" should be equal to 2278

  Scenario: Update avatar image
    When I send a POST request to "/api/v1/en_GB/accounts/@user@/avatar" with params:
      | key    | value                  |
      | upload | @avatar-test-image.png |
    Then the response status code should be 204

  Scenario: Download updated uploaded avatar image
    When I send a GET request to "/api/v1/en_GB/accounts/@user@/avatar"
    Then the response status code should be 200
    And the header "content-length" should be equal to 607

  Scenario: Download uploaded avatar image with invalid uuid
    When I send a GET request to "/api/v1/en_GB/accounts/aaa-aa-aaa/avatar"
    Then the response status code should be 404

  Scenario: Download uploaded avatar image with with not existing uuid
    And I send a GET request to "api/v1/accounts/01730e8d-fb8d-5afe-aaaa-b621bacbbaaa/avatar"
    Then the response status code should be 404

  Scenario: Delete avatar image
    When I send a DELETE request to "/api/v1/en_GB/accounts/@user@/avatar"
    Then the response status code should be 204


  Scenario: Create user avatar free
    When I send a POST request to "/api/v1/en_GB/accounts" with body:
      """
      {
          "email": "@@random_uuid@@@ergonode.com",
          "firstName": "Test",
          "lastName": "Test",
          "language": "en_GB",
          "password": 12345678,
          "passwordRepeat": 12345678,
          "roleId": "@role@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "user_avatar_free"

  Scenario: Download updated uploaded avatar image
    When I send a GET request to "/api/v1/en_GB/accounts/@user_avatar_free@/avatar"
    Then the response status code should be 404
