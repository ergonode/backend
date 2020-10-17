Feature: Ergonode import module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

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
      | status    | Processing  |
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
      | status    | Stopped           |
    And the JSON node "errors" should not be null
    And the JSON node "records" should not be null
    And the JSON node "created_at" should not be null
    And the JSON node "updated_at" should not be null
    And the JSON node "started_at" should not be null
    And the JSON node "ended_at" should exist

  Scenario: Get error import grid
    When I send a GET request to "/api/v1/en_GB/sources/@source_id@/imports/@import_id@/errors"
    Then the response status code should be 200
