Feature: channel module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create File Channel
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "file",
          "format": "csv",
          "export_type": "full",
          "name": "File export",
          "languages" : ["pl_PL"]
        }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

  Scenario: Get channel scheduler
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@/scheduler"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | id | @channel_id@ |
    And the JSON node "start" should be null
    And the JSON node "hour" should be null
    And the JSON node "minute" should be null
    And the JSON node "active" should be false

  Scenario: Update channel scheduler (inactive)
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@/scheduler" with body:
      """
        {
          "active": false,
          "start": "2020-01-01T10:00:00Z",
          "hour": 2147,
          "minute" : 0
        }
      """
    Then the response status code should be 204

  Scenario: Update channel scheduler
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@/scheduler" with body:
      """
        {
          "active": "true",
          "start": "2020-01-01T10:00:00Z",
          "hour": 2147483647,
          "minute" : 10
        }
      """
    Then the response status code should be 204

  Scenario: Get channel scheduler after update
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@/scheduler"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | id     | @channel_id@ |
      | hour   | 2147483647   |
      | minute | 10           |
    And the JSON node "start" should not be null
    And the JSON node "active" should be true

  Scenario: Update channel scheduler active without required fields
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@/scheduler" with body:
      """
        {
          "active": "true",
          "start": null,
          "hour": null,
          "minute" : null
        }
      """
    Then the response status code should be 400
    And the JSON node "errors.start" should exist
    And the JSON node "errors.hour" should exist
    And the JSON node "errors.minute" should exist

  Scenario: Update channel scheduler with to high hour value
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@/scheduler" with body:
      """
        {
          "active": "true",
          "start": "2020-01-01T10:00:00Z",
          "hour": 2147483648,
          "minute" : 10
        }
      """
    Then the response status code should be 400
