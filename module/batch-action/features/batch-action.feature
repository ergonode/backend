Feature: Text attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: Create batch action - validation error
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
      """
      {
          "action": "<action>",
          "ids": <ids>
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.<error_column>" should exist
    Examples:
      | type | ids | error_column |
      | | ["ca0bc7e6-e1cf-48a6-ae2d-745155c9aa63"] | type |
      | to_long_code_12345678901234567890 | ["ca0bc7e6-e1cf-48a6-ae2d-745155c9aa63"] | type |
      | type | ["not uuid"] | ids |
      | type | [] | ids |
