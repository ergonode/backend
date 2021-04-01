Feature: Core module

  Scenario: Create unit (not authenticated)
    When I send a POST request to "/api/v1/en_GB/units"
    Then the response status code should be 401

  Scenario: Get translation language (not authenticated)
    When I send a GET request to "/api/v1/en_GB/languages"
    Then the response status code should be 401

  Scenario: Update language (not authenticated)
    When I send a PUT request to "/api/v1/en_GB/languages"
    Then the response status code should be 405

  Scenario: Get language autocomplete (not authenticated)
    When I send a GET request to "/api/v1/en_GB/languages/autocomplete"
    Then the response status code should be 401
