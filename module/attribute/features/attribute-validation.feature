Feature: Attribute value validation feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create multi select attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "MULTI_SELECT_@@random_code@@",
        "type": "MULTI_SELECT",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_attribute"

  Scenario: Create option 1 for multiselect attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@multi_select_attribute@/options" with body:
      """
      {
        "code": "key_aa_@@random_code@@",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_1"

  Scenario: Create option 2 for multiselect attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@multi_select_attribute@/options" with body:
      """
      {
        "code": "key_bb_@@random_code@@",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_2"

  Scenario: Validate attribute value
    When I send a POST request to "api/v1/en_GB/attribute/@multi_select_attribute@/validate" with body:
      """
      {
        "value": ["@multi_select_option_1@", "@multi_select_option_2@"]
      }
      """
    Then the response status code should be 200

  Scenario: Validate attribute value (duplicated value)
    When I send a POST request to "api/v1/en_GB/attribute/@multi_select_attribute@/validate" with body:
      """
      {
        "value": ["@multi_select_option_1@", "@multi_select_option_1@"]
      }
      """
    Then the response status code should be 400
