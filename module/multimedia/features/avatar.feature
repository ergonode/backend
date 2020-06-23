Feature: Avatar
  In order to mange Avatar
  I need to be able to create and retrieve through the API.

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"

  Scenario: Upload new avatar file
    When I send a POST request to "/api/v1/avatar/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "avatar_id"

  Scenario: Upload same avatar file again
    When I send a POST request to "/api/v1/avatar/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist

  Scenario: Upload new avatar file with unsupported extension
    When I send a POST request to "/api/v1/avatar/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.abc |
    Then the response status code should be 400

  Scenario: Upload new avatar file without uploaded file
    When I send a POST request to "/api/v1/avatar/upload"
    Then the response status code should be 400

  Scenario: Upload new avatar with empty file
    When I send a POST request to "/api/v1/avatar/upload" with params:
      | key    | value                            |
      | upload | @multimedia-test-empty-image.png |
    Then the response status code should be 400

  Scenario: Download uploaded avatar file
    And I send a GET request to "api/v1/avatar/@avatar_id@"
    Then the response status code should be 200

  Scenario: Download uploaded avatar file with invalid uuid
    And I send a GET request to "api/v1/avatar/aaa-aa-aaa"
    Then the response status code should be 400

  Scenario: Download uploaded avatar file with with not existing uuid
    And I send a GET request to "api/v1/avatar/01730e8d-fb8d-5afe-aaaa-b621bacbbaaa"
    Then the response status code should be 404
