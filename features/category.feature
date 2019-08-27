Feature: Category module

  Scenario: Create category
    Given Current authentication token
    Given the request body is:
      """
      {
        "code": "CATEGORY_@@uuid@@",
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
    Given Current authentication token
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
    Then created response is received

  Scenario: Update category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update category (not found)
    Given Current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get category
    Given Current authentication token
    When I request "/api/v1/EN/categories/@category@" using HTTP GET
    Then the response code is 200

  Scenario: Get category (not authorized)
    When I request "/api/v1/EN/categories/@category@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get category (not found)
    Given Current authentication token
    When I request "/api/v1/EN/categories/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get categories (order by code)
    Given Current authentication token
    When I request "/api/v1/EN/categories?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by name)
    Given Current authentication token
    When I request "/api/v1/EN/categories?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by elements_count)
    Given Current authentication token
    When I request "/api/v1/EN/categories?field=elements_count" using HTTP GET
    Then grid response is received

  Scenario: Get categories (order by sequence)
    Given Current authentication token
    When I request "/api/v1/EN/categories?field=sequence" using HTTP GET
    Then grid response is received

  Scenario: Get categories (not authorized)
    When I request "/api/v1/EN/categories" using HTTP GET
    Then unauthorized response is received

  # TODO Check categories with all filters
  # TODO Check create category action with all incorrect possibilities
  # TODO Check update category action with all incorrect possibilities
