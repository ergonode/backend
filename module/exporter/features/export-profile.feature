Feature: Export Profile module

  Scenario: Get profile type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/dictionary/export-profile"
    Then the response status code should be 200
