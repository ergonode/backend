Feature: Export Profile module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get profile type
    When I send a GET request to "/api/v1/en/dictionary/export-profile"
    Then the response status code should be 200
    And the JSON node "file" should exist

  Scenario: Get profile configuration
    When I send a GET request to "/api/v1/en/export-profile/file/configuration"
    Then the response status code should be 200
    And the JSON node "properties.name" should exist
    And the JSON node "properties.format" should exist

  Scenario: Create File Export profile
    When I send a POST request to "/api/v1/en/export-profile" with body:
      """
        {
          "type": "file",
          "format": "xml",
          "name": "File export"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "export_profile_id"

  Scenario: Get export profile
    When I send a GET request to "/api/v1/en/export-profile/@export_profile_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | name   | File export         |
      | id     | @export_profile_id@ |
      | format | xml                 |

  Scenario: Create channel
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "export_profile_id": "@export_profile_id@",
        "name": "file_channel_@export_profile_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

  Scenario: Run export for xml
    When I send a POST request to "/api/v1/en/channels/@channel_id@/start"
    Then the response status code should be 201

  Scenario: Update File Export profile
    When I send a PUT request to "/api/v1/en/export-profile/@export_profile_id@" with body:
      """
        {
          "type": "file",
          "format": "csv",
          "name": "File export"
        }
      """
    Then the response status code should be 204

  Scenario: Get export profile after update
    When I send a GET request to "/api/v1/en/export-profile/@export_profile_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | name   | File export         |
      | id     | @export_profile_id@ |
      | format | csv                 |

  Scenario: Run export for csv
    When I send a POST request to "/api/v1/en/channels/@channel_id@/start"
    Then the response status code should be 201
