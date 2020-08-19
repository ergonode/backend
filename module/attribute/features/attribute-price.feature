Feature: Price attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create price attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "currency": "PLN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Get created price attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                  | @attribute_id@ |
      | type                | PRICE          |
      | scope               | local          |
      | parameters.currency | PLN            |

  Scenario: Create price attribute without required currency parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 400

  Scenario: Create price attribute with invalid currency parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "currency": null
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update price attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
        "groups": [],
        "scope": "local",
        "parameters": {"currency": "PLN"}
      }
      """
    Then the response status code should be 204

  Scenario: Delete price attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
