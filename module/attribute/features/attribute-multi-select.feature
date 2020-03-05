Feature: Multiselect attribute manipulation

  Scenario: Create multiselect attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "MULTISELECT_@@random_code@@",
          "type": "MULTI_SELECT",
          "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "multiselect_attribute"

  Scenario: Update multiselect attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "MULTI_SELECT",
          "groups": []
      }
      """
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete multiselect attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP DELETE
    Then empty response is received