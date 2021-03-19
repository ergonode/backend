Feature: Core module not authorized

  Scenario: Get translation language (not authorized)
    When I send a GET request to "/api/v1/en_GB/languages"
    Then the response status code should be 401

  Scenario: Get languages (not authorized)
    When I send a GET request to "/api/v1/en_GB/languages"
    Then the response status code should be 401

  Scenario: Update language (not authorized)
    When I send a PUT request to "/api/v1/en_GB/languages"
    Then the response status code should be 405
