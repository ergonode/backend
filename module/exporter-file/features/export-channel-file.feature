Feature: Export Profile module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get profile type
    When I send a GET request to "/api/v1/en_GB/dictionary/channels"
    Then the response status code should be 200
    And the JSON node "file" should exist

  Scenario: Get profile configuration
    When I send a GET request to "/api/v1/en_GB/channels/file/configuration"
    Then the response status code should be 200
    And the JSON node "properties.name" should exist
    And the JSON node "properties.format" should exist
    And the JSON node "properties.languages" should exist

  Scenario: Create File Channel
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "file",
          "format": "csv",
          "name": "File export",
          "languages" : ["pl_PL"]
        }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

  Scenario: Get channel
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | name         | File export  |
      | id           | @channel_id@ |
      | format       | csv          |
      | languages[0] | pl_PL        |

  Scenario: Update File Channel
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@" with body:
      """
        {
          "type": "file",
          "format": "csv",
          "name": "File export",
          "languages" : ["pl_PL"]
        }
      """
    Then the response status code should be 204

  Scenario: Run export for csv
    When I send a POST request to "/api/v1/en_GB/channels/@channel_id@/exports"
    Then the response status code should be 201
    And store response param "id" as "export_id"

  Scenario: Get export grid for given channel
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@/exports"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].status | ENDED       |
      | collection[0].id     | @export_id@ |
      | info.count           | 1           |

  Scenario: Get export information
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@/exports/@export_id@"
    Then the response status code should be 200
    And the JSON node "_links.attachment.href" should exist
    And the JSON node "started_at" should exist
    And the JSON node "ended_at" should exist
    And the JSON node "processed" should exist
    And the JSON nodes should contain:
      | id                       | @export_id@ |
      | status                   | ENDED       |
      | _links.attachment.method | GET         |
      | errors                   | 0           |

  Scenario: Get error list for export
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@/exports/@export_id@/errors"
    Then the response status code should be 200

  Scenario: Get download file for export
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@/exports/@export_id@/download"
    Then the response status code should be 200
