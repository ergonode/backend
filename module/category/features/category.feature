Feature: Category module

  Scenario: Create category
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category"

  Scenario: Create category (not authorized)
    When I send a POST request to "/api/v1/EN/categories"
    Then the response status code should be 401

  Scenario: Create category (no Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Create category (empty Name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
        }
      }
      """
    Then the response status code should be 201

  Scenario: Create category (name with language with empty string value)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/categories" with body:
      """
      {
        "code": "TREE_CAT_@@random_code@@",
        "name": {
          "DE": "",
          "EN": "Test EN"
        }
      }
      """
    Then the response status code should be 400

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Create category (name with wrong language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a POST request to "/api/v1/EN/categories" with body:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "test": "Test DE",
#          "EN": "Test EN"
#        }
#      }
#      """
#    Then the response status code should be 400

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Create category (name with no existing language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a POST request to "/api/v1/EN/categories" with body:
#      """
#      {
#        "code": "TREE_CAT_@@random_code@@",
#        "name": {
#          "ZZ": "Test DE",
#          "EN": "Test EN"
#        }
#      }
#      """
#    Then the response status code should be 400

  Scenario: Update category
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/categories/@category@" with body:
      """
      {
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update category (not authorized)
    When I send a PUT request to "/api/v1/EN/categories/@category@"
    Then the response status code should be 401

  Scenario: Update category (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update category (empty name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/categories/@category@" with body:
      """
      {
        "name": {
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update category (wrong parameter)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/categories/@category@" with body:
      """
      {
        "test": {
        }
      }
      """
    Then the response status code should be 400

#  TODO 500 : Code "test" is not valid language code
#  Scenario: Update category (wrong language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a PUT request to "/api/v1/EN/categories/@category@" with body:
#      """
#      {
#        "name": {
#          "test": "Test DE (changed)",
#          "EN": "Test EN (changed)"
#        }
#      }
#      """
#    Then the response status code should be 400

#  TODO 500 : Code "ZZ" is not valid language code
#  Scenario: Update category (incorrect language code)
#    Given I am Authenticated as "test@ergonode.com"
#    And I add "Content-Type" header equal to "application/json"
#    And I add "Accept" header equal to "application/json"
#    When I send a PUT request to "/api/v1/EN/categories/@category@" with body:
#      """
#      {
#        "name": {
#          "ZZ": "Test DE (changed)",
#          "EN": "Test EN (changed)"
#        }
#      }
#      """
#    Then the response status code should be 400

  Scenario: Update category (empty translation)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/categories/@category@" with body:
      """
      {
        "name": {
          "DE": "",
          "EN": "Test EN (changed)"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get category
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories/@category@"
    Then the response status code should be 200

  Scenario: Get category (not authorized)
    When I send a GET request to "/api/v1/EN/categories/@category@"
    Then the response status code should be 401

  Scenario: Get category (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category (not authorized)
    When I send a DELETE request to "/api/v1/EN/categories/@category@"
    Then the response status code should be 401

  Scenario: Delete category (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/categories/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete category
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/categories/@category@"
    Then the response status code should be 204

  Scenario: Get categories (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order by elements_count)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?field=elements_count"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order by sequence)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?field=sequence"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?field=name&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?field=name&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by sequence)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=sequence%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=code%3DCAT"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by elements_count)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count%3D1"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get categories (filter by elements_count = 0)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count=0"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get categories (filter by elements_count = 9999999)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (filter by elements_count >= 9999999)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count>=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (filter by elements_count <= 9999999)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count<=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get categories (filter by elements_count >= 888888 <= 9999999)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count>=8888888;elements_count<=9999999"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get categories (not authorized)
    When I send a GET request to "/api/v1/EN/categories"
    Then the response status code should be 401
