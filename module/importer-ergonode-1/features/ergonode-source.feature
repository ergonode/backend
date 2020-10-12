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

  Scenario: Update Ergonode ZIP Source with null attribute
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
    Then the response status code should be 400
