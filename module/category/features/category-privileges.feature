Feature: Category module

  Scenario: Create category (not authenticated)
    When I send a POST request to "/api/v1/en_GB/categories"
    Then the response status code should be 401

  Scenario: Update category (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/categories/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get category (not authenticated)
    When I send a GET request to "/api/v1/en_GB/categories/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get categories (not authenticated)
    When I send a GET request to "/api/v1/en_GB/categories"
    Then the response status code should be 401

  Scenario: Delete category (not authenticated)
    When I send a DELETE request to "/api/v1/en_GB/categories/@@random_uuid@@"
    Then the response status code should be 401
