Feature: Price attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create price attribute
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "parameters":
        {
          "currency": "PLN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create price attribute without required currency parameter
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": []
      }
      """
    Then the response status code should be 400

  Scenario: Create price attribute with invalid currency parameter
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "parameters":
        {
          "currency": "incorrect value"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update price attribute
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id@" with body:
      """
      {
        "groups": [],
        "parameters": {"currency": "PLN"}
      }
      """
    Then the response status code should be 204

  Scenario: Delete price attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 204