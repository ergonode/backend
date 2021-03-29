Feature: Completeness module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get completeness widget data
    When I send a GET request to "/api/v1/en_GB/dashboard/widget/completeness-count"
    Then the response status code should be 200
