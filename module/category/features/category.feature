Feature: Category module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: Create category with invalid data (<message>)
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": <code>,
        "name": <name>
      }
      """
    Then the response status code should be 400
    And the JSON node "<node>" should exist
    And the JSON nodes should be equal to:
      | <node> | <message> |
    Examples:
      | node                 | name                                          | code                                                                                                                                                  | message                                                             |
      | errors.code[0]       | {"en_EN":"label"}                             | ""                                                                                                                                                    | System name is required                                             |
      | errors.code[0]       | {"en_EN":"label"}                             | "!@"                                                                                                                                                  | System name can have only letters, digits or underscore symbol      |
      | errors.code[0]       | {"en_EN":"label"}                             | "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii" | System name is too long. It should contain 128 characters or less.  |
      | errors.name          | ""                                            | "Code"                                                                                                                                                | Translation is not valid                                            |
      | errors.name.en_EN[0] | {"en_EN":""}                                  | "Code"                                                                                                                                                | This value should not be blank.                                     |
      | errors.name.en_EN[0] | {"en_EN":"iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii"} | "Code"                                                                                                                                                | Category name is too long. It should contain 32 characters or less. |


  Scenario: Create category
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {
          "de_DE": "Test de",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category"

  Scenario: Get category
    When I send a GET request to "/api/v1/en_GB/categories/@category@"
    Then the response status code should be 200
    And store response param "code" as "category_code"

  Scenario: Create category with exists code
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "@category_code@",
        "name": {
          "de_DE": "Test de",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      |  errors.code[0]  | The category code is not unique. |

  Scenario: Update category
    When I send a PUT request to "/api/v1/en_GB/categories/@category@" with body:
      """
      {
        "name": {
          "de_DE": "Test de",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update category (not found)
    When I send a PUT request to "/api/v1/en_GB/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update category (empty name)
    When I send a PUT request to "/api/v1/en_GB/categories/@category@" with body:
      """
      {
        "name": {
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update category (wrong parameter)
    When I send a PUT request to "/api/v1/en_GB/categories/@category@" with body:
      """
      {
        "test": {
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update category (empty translation)
    When I send a PUT request to "/api/v1/en_GB/categories/@category@" with body:
      """
      {
        "name": {
          "de_DE": "",
          "en_GB": "Test en (changed)"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get category (not found)
    When I send a GET request to "/api/v1/en_GB/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category (not found)
    When I send a DELETE request to "/api/v1/en_GB/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario Outline: Get category grid for field (<field>)
    When I send a GET request to "/api/v1/en_GB/categories?field=<field>&order=<order>&filter=<filter>"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    Examples:
      | field          | order | filter         |
      | code           | ASC   | code           |
      | name           | DESC  | name           |
      | elements_count | ASC   | elements_count |
      | sequence       | DESC  | sequence       |

  Scenario: Get categories (filter by elements_count = 0)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count=0"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/1/"

  Scenario: Get categories (filter by elements_count = 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count=9999999"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (filter by elements_count >= 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count>=9999999"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (filter by elements_count <= 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count<=9999999"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/1/"

  Scenario: Get categories (filter by elements_count >= 888888 <= 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count>=8888888;elements_count<=9999999"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get category configuration
    When I send a GET request to "/api/v1/en_GB/categories/DEFAULT/configuration"
    Then the response status code should be 200
    And the JSON node "properties.code" should exist
    And the JSON node "properties.name" should exist

  Scenario: Get categories types dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/categories/types"
    Then the response status code should be 200
    And the JSON node "DEFAULT" should exist

  Scenario: Delete category
    When I send a DELETE request to "/api/v1/en_GB/categories/@category@"
    Then the response status code should be 204

  Scenario: Get category
    When I send a GET request to "/api/v1/en_GB/categories/@category@"
    Then the response status code should be 404

  Scenario: Get categories (filter by elements_count = 0)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count=0"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"
