Feature: Text-area attribute manipulation

  Scenario: Create textarea attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_AREA_@@random_code@@",
          "type": "TEXT_AREA",
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "textarea_attribute"

  Scenario: Update textarea attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "TEXT_AREA",
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes/@textarea_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete textarea attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@textarea_attribute@" using HTTP DELETE
    Then empty response is received