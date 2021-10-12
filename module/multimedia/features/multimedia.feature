Feature: Multimedia
  In order to mange Multimedia
  I need to be able to create and retrieve through the API.

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"

  Scenario: Upload new multimedia file
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_id_1"

  Scenario: Upload same multimedia file again
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 400

  Scenario: Upload new multimedia file with unsupported extension
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.abc |
    Then the response status code should be 400

  Scenario: Upload new multimedia file without uploaded file
    When I send a POST request to "/api/v1/multimedia/upload"
    Then the response status code should be 400

  Scenario: Upload new multimedia with empty file
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                            |
      | upload | @multimedia-test-empty-image.png |
    Then the response status code should be 400

  Scenario: Get multimedia
    And I send a GET request to "api/v1/en_EN/multimedia/@multimedia_id_1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id        | @multimedia_id_1@ |
      | extension | png             |
      | mime      | image/png       |

  Scenario: Update multimedia information
    And I send a PUT request to "api/v1/en_EN/multimedia/@multimedia_id_1@" with body:
       """
      {
        "name": "new-name.png",
        "alt": {
          "pl_PL": "pl alt"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Get multimedia
    And I send a GET request to "api/v1/en_EN/multimedia/@multimedia_id_1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id        | @multimedia_id_1@ |
      | extension | png             |
      | mime      | image/png       |

  Scenario: Upload second multimedia file (same file different name)
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_id_2"

  Scenario: Update multimedia information (with existing name)
    And I send a PUT request to "api/v1/en_EN/multimedia/@multimedia_id_2@" with body:
       """
      {
        "name": "new-name.png",
         "alt": {
             "pl_PL": "pl alt"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update multimedia information (edit alt only)
    And I send a PUT request to "api/v1/en_EN/multimedia/@multimedia_id_2@" with body:
       """
      {
        "name": "multimedia-test-image.png",
        "alt": {
          "pl_PL": "new pl alt"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Download multimedia file
    And I send a GET request to "api/v1/multimedia/@multimedia_id_1@/download"
    Then the response status code should be 200

  Scenario: Download multimedia file thumbnail default
    And I send a GET request to "api/v1/multimedia/@multimedia_id_1@/download/default"
    Then the response status code should be 200

  Scenario: Download uploaded multimedia file with invalid uuid
    And I send a GET request to "api/v1/multimedia/aaa-aa-aaa"
    Then the response status code should be 404

  Scenario: Download uploaded multimedia file with with not existing uuid
    And I send a GET request to "api/v1/multimedia/01730e8d-fb8d-5afe-aaaa-b621bacbbaaa"
    Then the response status code should be 404

  Scenario: Get multimedia grid
    And I send a GET request to "api/v1/en_GB/multimedia"
    Then the response status code should be 200
