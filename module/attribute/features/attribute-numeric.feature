Feature: Text attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create numeric attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "NUMERIC_@@random_code@@",
          "type": "NUMERIC",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Update numeric attribute
    And I send a "PUT" request to "/api/v1/en/attributes/@attribute_id@" with body:
      """
      {
        "type": "NUMERIC",
        "groups": [],
        "label":
        {
          "pl_PL": "pl_PL",
          "en": "en"
        },
        "placeholder":
        {
          "pl_PL": "pl_PL",
          "en": "en"
        },
        "hint": {
          "pl_PL": "pl_PL",
          "en": "en"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Get numeric attribute
    And I send a "GET" request to "/api/v1/en/attributes/@attribute_id@"
    Then the response status code should be 200

  Scenario: Delete numeric attribute
    And I send a "DELETE" request to "/api/v1/en/attributes/@attribute_id@"
    Then the response status code should be 204