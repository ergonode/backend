Feature: Export Profile module

  Scenario: Get profile type
    Given current authentication token
    When I request "/api/v1/EN/dictionary/export-profile" using HTTP GET
    Then the response code is 200
