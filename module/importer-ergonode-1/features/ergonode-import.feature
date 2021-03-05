Feature: Ergonode import module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    When I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "IMPORT_E1_TEST_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create Ergonode ZIP Source with default attribute
    When I send a POST request to "/api/v1/en_GB/sources" with body:
      """
      {
        "type": "ergonode-zip",
        "name": "default attribute"
      }
      """
    Then the response status code should be 201

  Scenario: Create Ergonode ZIP Source
    When I send a POST request to "/api/v1/en_GB/sources" with body:
      """
      {
        "type": "ergonode-zip",
        "name": "name",
        "import" : [
           "templates",
           "attributes",
           "categories",
           "products"
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "source_id"

  Scenario: Create Ergonode ZIP Source with empty body
    When I send a POST request to "/api/v1/en_GB/sources" with body:
     """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Get Ergonode ZIP Source
    When I send a GET request to "/api/v1/en_GB/sources/@source_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                     | ergonode-zip     |
      | name                     | name             |


  Scenario: Update Ergonode ZIP Source
    When I send a PUT request to "/api/v1/en_GB/sources/@source_id@" with body:
      """
      {
        "name": "name2",
        "import" : [
           "templates",
           "attributes",
           "categories",
           "products"
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "source_id"

  Scenario: Upload Ergonode test import file
    When I send a POST request to "/api/v1/en_GB/sources/@source_id@/upload" with params:
      | key    | value              |
      | upload | @ergonode-test.zip |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "import_id"

  Scenario: Upload Ergonode test import file with corrupted csv file
    When I send a POST request to "/api/v1/en_GB/sources/@source_id@/upload" with params:
      | key    | value                    |
      | upload | @ergonode-test-error.zip |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "error_import_id"

  Scenario: Get source imports grid
    When I send a GET request to "/api/v1/en_GB/sources/@source_id@/imports"
    Then the response status code should be 200

  Scenario: Get source import
    When I send a GET request to "/api/v1/en_GB/sources/@source_id@/imports/@import_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id        | @import_id@ |
      | source_id | @source_id@ |
      | status    | Ended       |
    And the JSON node "errors" should not be null
    And the JSON node "records" should not be null
    And the JSON node "created_at" should not be null
    And the JSON node "updated_at" should not be null
    And the JSON node "started_at" should not be null
    And the JSON node "ended_at" should exist

  Scenario: Get source error import
    When I send a GET request to "/api/v1/en_GB/sources/@source_id@/imports/@error_import_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id        | @error_import_id@ |
      | source_id | @source_id@       |
      | status    | Stopped             |
    And the JSON node "errors" should not be null
    And the JSON node "records" should not be null
    And the JSON node "created_at" should not be null
    And the JSON node "updated_at" should not be null
    And the JSON node "started_at" should not be null
    And the JSON node "ended_at" should exist

  Scenario: Get error import grid
    When I send a GET request to "/api/v1/en_GB/sources/@source_id@/imports/@import_id@/errors"
    Then the response status code should be 200
