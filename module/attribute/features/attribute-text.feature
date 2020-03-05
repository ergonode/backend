Feature: Text attribute manipulation

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "text_attribute"

  Scenario: Create text attribute without group relation
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "text_attribute_without_group"

  Scenario: Update text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "TEXT",
          "groups": [],
          "label": {"PL": "PL", "EN": "EN"},
          "placeholder": {"PL": "PL", "EN": "EN"},
          "hint": {"PL": "PL", "EN": "EN"},
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Update text attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update text attribute (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Delete text attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete text attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP DELETE
    Then empty response is received