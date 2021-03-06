Feature: Batch action manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get not exists batch action grid
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@@random_uuid@@/entries"
    Then the response status code should be 404
