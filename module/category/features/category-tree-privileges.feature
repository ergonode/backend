Feature: Category tree module

  Scenario: Get category trees (not authenticated)
    When I send a GET request to "/api/v1/en_GB/trees"
    Then the response status code should be 401

  Scenario: Update category tree (not authenticated)
    When I send a POST request to "/api/v1/en_GB/trees"
    Then the response status code should be 401

  Scenario: Get category tree (not authenticated)
    When I send a GET request to "/api/v1/en_GB/trees/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Update category tree (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/trees/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Delete category tree (not authenticated)
    When I send a DELETE request to "/api/v1/en_GB/trees/@@static_uuid@@"
    Then the response status code should be 401

