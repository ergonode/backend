Feature: Category module

  Scenario: Create category (not authorized)
    When I send a POST request to "/api/v1/en/categories"
    Then the response status code should be 401

  Scenario: Update category (not authorized)
    When I send a PUT request to "/api/v1/en/categories/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get category (not authorized)
    When I send a GET request to "/api/v1/en/categories/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get categories (not authorized)
    When I send a GET request to "/api/v1/en/categories"
    Then the response status code should be 401

  Scenario: Delete category (not authorized)
    When I send a DELETE request to "/api/v1/en/categories/@@random_uuid@@"
    Then the response status code should be 401