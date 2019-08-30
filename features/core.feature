Feature: Core module

  Scenario: Get languages
    Given current authentication token
    When I request "/api/v1/EN/dictionary/languages" using HTTP GET
    Then the response code is 200

  Scenario: Get languages (not authorized)
    When I request "/api/v1/EN/dictionary/languages" using HTTP GET
    Then unauthorized response is received
