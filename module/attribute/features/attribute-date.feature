Feature: Date attribute manipulation

  Scenario: Create date attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "DATE_@@random_code@@",
          "type": "DATE",
          "groups": [],
          "parameters": {"format": "yyyy-MM-dd"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "date_attribute"

  Scenario: Update date attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "DATE",
          "groups": [],
          "parameters": {"format": "yyyy-MM-dd"}
      }
      """
    When I request "/api/v1/EN/attributes/@date_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete date attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@date_attribute@" using HTTP DELETE
    Then empty response is received