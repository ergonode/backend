Feature: Workflow not authorized

  Background:
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create workflow (not authorized)
    And I send a "POST" request to "/api/v1/EN/workflow"
    Then the response status code should be 401

  Scenario: Update default workflow (not authorized)
    And I send a "PUT" request to "/api/v1/EN/workflow/default"
    Then the response status code should be 401

  Scenario: Get default workflow (not authorized)
    And I send a "GET" request to "/api/v1/EN/workflow/default"
    Then the response status code should be 401

  Scenario: Delete workflow (not authorized)
    And I send a "DELETE" request to "/api/v1/EN/workflow/default"
    Then the response status code should be 401
