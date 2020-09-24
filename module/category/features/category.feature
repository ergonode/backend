Feature: Category module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create category
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "type": "DEFAULT",
        "name": {
          "de_DE": "Test de",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category"

  Scenario: Create category (no Name)
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Create category (empty Name)
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
        }
      }
      """
    Then the response status code should be 201

  Scenario: Create category (name with language with empty string value)
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "type": "DEFAULT",
        "name": {
          "de_DE": "",
          "en_GB": "Test en"
        }
      }
      """
    Then the response status code should be 400

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Create category (name with wrong language code)
#    When I send a POST request to "/api/v1/en_GB/categories" with body:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "test": "Test de",
#          "en_GB": "Test en"
#        }
#      }
#      """
#    Then the response status code should be 400

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Create category (name with no existing language code)
#    When I send a POST request to "/api/v1/en_GB/categories" with body:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "ZZ": "Test de",
#          "en_GB": "Test en"
#        }
#      }
#      """
#    Then the response status code should be 400

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

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Update category (wrong language code)
#    When I send a PUT request to "/api/v1/en_GB/categories/@category@" with body:
#      """
#      {
#        "name": {
#          "test": "Test de (changed)",
#          "en_GB": "Test en (changed)"
#        }
#      }
#      """
#    Then the response status code should be 400

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Update category (incorrect language code)
#    When I send a PUT request to "/api/v1/en_GB/categories/@category@" with body:
#      """
#      {
#        "name": {
#          "ZZ": "Test de (changed)",
#          "en_GB": "Test en (changed)"
#        }
#      }
#      """
#    Then the response status code should be 400

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

  Scenario: Get category
    When I send a GET request to "/api/v1/en_GB/categories/@category@"
    Then the response status code should be 200

  Scenario: Get category (not found)
    When I send a GET request to "/api/v1/en_GB/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category (not found)
    When I send a DELETE request to "/api/v1/en_GB/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category
    When I send a DELETE request to "/api/v1/en_GB/categories/@category@"
    Then the response status code should be 204

  Scenario: Get categories (order by code)
    When I send a GET request to "/api/v1/en_GB/categories?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order by name)
    When I send a GET request to "/api/v1/en_GB/categories?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order by elements_count)
    When I send a GET request to "/api/v1/en_GB/categories?field=elements_count"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order by sequence)
    When I send a GET request to "/api/v1/en_GB/categories?field=sequence"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order ASC)
    When I send a GET request to "/api/v1/en_GB/categories?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order DESC)
    When I send a GET request to "/api/v1/en_GB/categories?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by sequence)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=sequence%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by name)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by code)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=code%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by elements_count)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by elements_count = 0)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count=0"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get categories (filter by elements_count = 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (filter by elements_count >= 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count>=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (filter by elements_count <= 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count<=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get categories (filter by elements_count >= 888888 <= 9999999)
    When I send a GET request to "/api/v1/en_GB/categories?limit=25&offset=0&filter=elements_count>=8888888;elements_count<=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get category configuration
    When I send a GET request to "/api/v1/en_GB/categories/DEFAULT/configuration"
    Then the response status code should be 200
    And the JSON node "properties.code" should exist
    And the JSON node "properties.name" should exist

  Scenario: Get attribute types dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/categories/types"
    Then the response status code should be 200
    And the JSON node "DEFAULT" should exist
