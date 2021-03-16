Feature: batch action module profile notification

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get batch action info
    When I send a GET request to "/api/v1/en_GB/profile/batch-action"
    Then the response status code should be 200
