Feature: collection module

  Scenario: Get collection autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/collections/autocomplete"
    Then the response status code should be 401

  Scenario: Get collection type  autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/collections/type/autocomplete"
    Then the response status code should be 401
