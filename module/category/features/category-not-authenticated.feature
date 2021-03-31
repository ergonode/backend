Feature: Category module

  Scenario: Get category autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/categories/autocomplete"
    Then the response status code should be 401

  Scenario: Get category tree autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/trees/autocomplete"
    Then the response status code should be 401
