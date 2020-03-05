Feature: Unit attribute manipulation

  Scenario: Create unit attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "UNIT_@@random_code@@",
          "type": "UNIT",
          "groups": [],
          "parameters": {"unit": "M"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "unit_attribute"

  Scenario: Update unit attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "UNIT",
          "groups": [],
          "parameters": {"unit": "M"}
      }
      """
    When I request "/api/v1/EN/attributes/@unit_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete unit attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@unit_attribute@" using HTTP DELETE
    Then empty response is received