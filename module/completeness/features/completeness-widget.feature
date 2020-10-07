Feature: Designer module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Gte completeness widget data
    When I send a GET request to "/api/v1/en_GB/dashboard/widget/completeness-count"
    And print last response
    Then the response status code should be 200