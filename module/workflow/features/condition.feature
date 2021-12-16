Feature: Workflow Condition

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get condition (not found)
    When I send a GET request to "/api/v1/en_GB/workflow/condition/asd"
    Then the response status code should be 404
