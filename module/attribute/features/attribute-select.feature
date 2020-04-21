Feature: Select attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create select attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "SELECT@@random_code@@",
          "type": "SELECT",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create option for attribute
    And I send a "POST" request to "/api/v1/en/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "option_1",
        "label":  {
          "pl": "Option pl 1",
          "en": "Option en 1"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "option_id"

  Scenario: Get created select
    And I send a "GET" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@"
    Then the response status code should be 200
    And the JSON node "label.pl" should contain "Option pl 1"
    And the JSON node "label.en" should contain "Option en 1"
    And the JSON node "code" should contain "option_1"

  Scenario: Update option for attribute
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@" with body:
      """
      {
        "code": "option_2",
        "label":  {
          "pl": "Option pl 2",
          "en": "Option en 2"
        }
      }
      """
    Then the response status code should be 201

  Scenario: Get created option
    And I send a "GET" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@"
    Then the response status code should be 200
    And the JSON node "label.pl" should contain "Option pl 2"
    And the JSON node "label.en" should contain "Option en 2"
    And the JSON node "code" should contain "option_2"

  Scenario: Get attribute options
    And I send a "GET" request to "/api/v1/EN/attributes/@attribute_id@/options"
    Then the response status code should be 200
    And the JSON node "[0].label.pl" should contain "Option pl 2"
    And the JSON node "[0].label.en" should contain "Option en 2"

  Scenario: Delete select attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 204