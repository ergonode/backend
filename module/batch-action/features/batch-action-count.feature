Feature: Batch action get templates

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Count for missing type
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "unsupported_type",
        "filter": {
          "query": "query"
        }
      }
      """
    Then the response status code should be 400
