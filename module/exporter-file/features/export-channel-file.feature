Feature: Export Profile module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get profile type
    When I send a GET request to "/api/v1/en/dictionary/channels"
    Then the response status code should be 200
    And the JSON node "file" should exist

  Scenario: Get profile configuration
    When I send a GET request to "/api/v1/en/channels/file/configuration"
    Then the response status code should be 200
    And the JSON node "properties.name" should exist
    And the JSON node "properties.format" should exist

  Scenario: Create File Channel
    When I send a POST request to "/api/v1/en/channels" with body:
      """
        {
          "type": "file",
          "format": "xml",
          "name": "File export"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

  Scenario: Get export profile
    When I send a GET request to "/api/v1/en/channels/@channel_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | name   | File export  |
      | id     | @channel_id@ |
      | format | xml          |

  Scenario: Run export for xml
    When I send a POST request to "/api/v1/en/channels/@channel_id@/exports"
    Then the response status code should be 201
    And store response param "id" as "export_1_id"

  Scenario: Update File Channel
    When I send a PUT request to "/api/v1/en/channels/@channel_id@" with body:
      """
        {
          "type": "file",
          "format": "csv",
          "name": "File export"
        }
      """
    Then the response status code should be 204

  Scenario: Run export for csv
    When I send a POST request to "/api/v1/en/channels/@channel_id@/exports"
    Then the response status code should be 201
    And store response param "id" as "export_2_id"

  Scenario: Get export grid for given channel
    When I send a GET request to "/api/v1/en/channels/@channel_id@/exports"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].status | ENDED         |
      | collection[0].id     | @export_1_id@ |
      | collection[1].status | ENDED         |
      | collection[1].id     | @export_2_id@ |
      | info.count           | 2             |

  Scenario: Get first export information
    When I send a GET request to "/api/v1/en/channels/@channel_id@/exports/@export_1_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | id         | @export_1_id@ |
      | channel_id | @channel_id@  |
      | status     | ENDED         |

  Scenario: Get first error export error list
    When I send a GET request to "/api/v1/en/channels/@channel_id@/exports/@export_1_id@/errors"
    Then the response status code should be 200

  Scenario: Get second export information
    When I send a GET request to "/api/v1/en/channels/@channel_id@/exports/@export_2_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | id         | @export_2_id@ |
      | channel_id | @channel_id@  |
      | status     | ENDED         |

  Scenario: Get second error export error list
    When I send a GET request to "/api/v1/en/channels/@channel_id@/exports/@export_2_id@/errors"
    Then the response status code should be 200