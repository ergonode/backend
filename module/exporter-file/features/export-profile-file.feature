Feature: Export Profile module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get profile type
    When I send a GET request to "/api/v1/en/dictionary/export-profile"
    Then the response status code should be 200
    And the JSON node "file" should exist

  Scenario: Create File Export profile
    When I send a POST request to "/api/v1/en/export-profile" with body:
      """
        {
          "type": "file",
          "name": "File export"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "export_profile_id"

  Scenario: Get export profile
    When I send a GET request to "/api/v1/en/export-profile/@export_profile_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | name | File export         |
      | id   | @export_profile_id@ |
