Feature: Magento 1 CSV module

  Scenario: Create Magento 1 CSV Source
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/sources" with body:
      """
      {
        "type": "magento-1-csv",
        "name": "name",
        "host": "http://test.host",
        "import" : [
           "templates",
           "attributes",
           "categories",
           "products"
        ],
        "mapping": {
          "default_language": "EN",
          "languages": [
              {
                 "store":"test",
                 "language":"EN"
              }
          ]
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "source_id"

  Scenario: Get Magento 1 CSV Source
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/sources/@source_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                     | magento-1-csv    |
      | name                     | name             |
      | host                     | http://test.host |
      | mapping.default_language | EN               |


  Scenario: Update Magento 1 CSV Source
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/sources/@source_id@" with body:
      """
      {
        "name": "name2",
        "host": "http://test.host",
        "import" : [
           "templates",
           "attributes",
           "categories",
           "products"
        ],
        "mapping": {
          "default_language": "EN",
          "languages": []
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "source_id"

  Scenario: Get Magento 1 CSV Source after update
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/sources/@source_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                     | magento-1-csv    |
      | name                     | name2            |
      | host                     | http://test.host |
      | mapping.default_language | EN               |

  Scenario: Upload magento 1 test import file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/sources/@source_id@/upload" with params:
      | key    | value               |
      | upload | @magento-1-test.csv |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "import_id"

  Scenario: Get source imports grid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/sources/@source_id@/imports"
    Then the response status code should be 200

  Scenario: Get source import grid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/sources/@source_id@/imports/@import_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id        | @import_id@ |
      | source_id | @source_id@ |