Feature: Segment module

  Scenario: Create segment (not authenticated)
    When I send a POST request to "/api/v1/en_GB/segments"
    Then the response status code should be 401

  Scenario: Update segment (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/segments/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get segment (not authenticated)
    When I send a GET request to "/api/v1/en_GB/segments/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get segments (not authenticated)
    When I send a GET request to "/api/v1/en_GB/segments"
    Then the response status code should be 401

  Scenario: Get products based on segment (not authenticated)
    When I send a GET request to "/api/v1/en_GB/segments/@@random_uuid@@/products"
    Then the response status code should be 401

  Scenario: Delete segment (not authenticated)
    When I send a DELETE request to "/api/v1/en_GB/segments/@@random_uuid@@"
    Then the response status code should be 401

  Scenario: Get segment autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/segments/autocomplete"
    Then the response status code should be 401
