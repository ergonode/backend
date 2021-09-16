Feature: Batch action manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create batch action - unsupported type
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
      """
      {
        "type": "incorrect_type",
        "filter": null
      }
      """
    Then the response status code should be 400

  Scenario: End batch action - not exists
    And I send a "PUT" request to "/api/v1/en_GB/batch-action/@@random_uuid@@/end"
    Then the response status code should be 404

  Scenario: Get not exists batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@@random_uuid@@"
    Then the response status code should be 404
