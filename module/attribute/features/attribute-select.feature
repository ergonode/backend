Feature: Select attribute manipulation

  Scenario: Create select attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "SELECT_@@random_code@@",
          "type": "SELECT",
          "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "select_attribute"

  Scenario: Update select attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "SELECT",
          "groups": []
      }
      """
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete select attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP DELETE
    Then empty response is received