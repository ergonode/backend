Feature: Price attribute manipulation

  Scenario: Create price attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "PRICE_@@random_code@@",
          "type": "PRICE",
          "groups": [],
          "parameters": {"currency": "PLN"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "price_attribute"

  Scenario: Update price attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "PRICE",
          "groups": [],
          "parameters": {"currency": "PLN"}
      }
      """
    When I request "/api/v1/EN/attributes/@price_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete price attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@price_attribute@" using HTTP DELETE
    Then empty response is received