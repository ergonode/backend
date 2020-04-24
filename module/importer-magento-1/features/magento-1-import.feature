Feature: Magento 1 CSV module

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "IMPORT_M1_TEST_@@random_code@@",
          "type": "TEXT",
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create Magento 1 CSV Source
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/sources" with body:
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
          "default_language": "en",
          "languages": [
              {
                 "store":"test",
                 "language":"en"
              }
          ]
        },
        "attributes": [
          {
            "code": "name",
            "attribute": "@attribute_id@"
          }
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "source_id"

  Scenario: Create Magento 1 CSV Source with null attribute id value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/sources" with body:
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
          "default_language": "en",
          "languages": [
            {
               "store":"test",
               "language":"en"
            }
          ]
        },
        "attributes": [
           {

           }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Create Magento 1 CSV Source with empty attribute collection object
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/sources" with body:
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
          "default_language": "en",
          "languages": [
            {
               "store":"test",
               "language":"en"
            }
          ]
        },
        "attributes": [
          {}
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Create Magento 1 CSV Source with not exists attribute id value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/sources" with body:
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
          "default_language": "en",
          "languages": [
            {
               "store":"test",
               "language":"en"
            }
          ]
        },
        "attributes": [
           {
             "code": "name-3",
             "attribute": "ad089ed5-92e0-4c1a-875c-430cde785e3f"
           }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Get Magento 1 CSV Source
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/sources/@source_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                     | magento-1-csv    |
      | name                     | name             |
      | host                     | http://test.host |
      | mapping.default_language | en               |


  Scenario: Update Magento 1 CSV Source
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/sources/@source_id@" with body:
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
          "default_language": "en",
          "languages": []
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "source_id"

  Scenario: Update Magento 1 CSV Source with null attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/sources/@source_id@" with body:
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
          "default_language": "en",
          "languages": []
        },
        "attributes": [
           {
             "code": "name-2",
             "attribute": null
           }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Update Magento 1 CSV Source with note exists attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/sources/@source_id@" with body:
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
          "default_language": "en",
          "languages": []
        },
        "attributes": [
           {
             "code": "name-2",
             "attribute": "5bbd9479-e8d4-4a7c-8771-be29248df7d6"
           }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Get Magento 1 CSV Source after update
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/sources/@source_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                     | magento-1-csv    |
      | name                     | name2            |
      | host                     | http://test.host |
      | mapping.default_language | en               |

  Scenario: Upload magento 1 test import file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/sources/@source_id@/upload" with params:
      | key    | value               |
      | upload | @magento-1-import.csv |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "import_id"

  Scenario: Get source imports grid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/sources/@source_id@/imports"
    Then the response status code should be 200

  Scenario: Get source import grid
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/sources/@source_id@/imports/@import_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id        | @import_id@ |
      | source_id | @source_id@ |