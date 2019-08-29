Feature: Category module

  Scenario: Create category
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received
    And remember response param "id" as "category"

  Scenario: Create category (not authorized)
    When I request "/api/v1/EN/categories" using HTTP POST
    Then unauthorized response is received

  Scenario: Update category
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "DE": "Test DE",
          "EN": "Test EN"
        }
      }
      """
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then empty response is received

  Scenario: Update category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update category (not found)
    Given current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get category
    Given current authentication token
    When I request "/api/v1/EN/categories/@category@" using HTTP GET
    Then the response code is 200

  Scenario: Get category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get category (not found)
    Given current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get categories (order by code)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by name)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=elements_count" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by sequence)
    Given current authentication token
    When I request "/api/v1/EN/categories?field=sequence" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by sequence)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=sequence%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=code%3DCAT" using HTTP GET
    Then grid response is received

  Scenario: Get categories (filter by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/categories?limit=25&offset=0&filter=elements_count%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get categories (not authorized)
    When I request "/api/v1/EN/categories" using HTTP GET
    Then unauthorized response is received

  # TODO Check create category action with all incorrect possibilities
  # TODO Check update category action with all incorrect possibilities
