Feature: Attribute validation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create NONE TYPE attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@@random_code@@",
        "type": "NONE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create NOT TYPE attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@@random_code@@",
        "groups": [],
        "scope": "local",
        "parameters":
        {
        }
      }
      """
    Then the response status code should be 400
