Feature: Product module

  Scenario: Get products history (not authenticated)
    When I send a GET request to "/api/v1/en_GB/products/@@random_uuid@@/history"
    Then the response status code should be 401

  Scenario: Get product autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/products/autocomplete"
    Then the response status code should be 401
