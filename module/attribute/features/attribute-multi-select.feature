Feature: Multi multi select attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create multi select attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "MULTI_SELECT@@random_code@@",
          "type": "MULTI_SELECT",
          "scope": "local",
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

  Scenario: Create option for attribute (option already exists)
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
    Then the response status code should be 400

  Scenario: Create second option for attribute
    And I send a "POST" request to "/api/v1/en/attributes/@attribute_id@/options" with body:
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
  Scenario: Get created multi select
    And I send a "GET" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@"
    Then the response status code should be 200

  Scenario: Update option for attribute
    And I send a "PUT" request to "/api/v1/en/attributes/@attribute_id@/options/@option_id@" with body:
      """
     {
        "code": "option_3",
        "label":  {
          "pl": "Option pl 3",
          "en": "Option en 3"
        }
      }
      """
    Then the response status code should be 201

  Scenario: Update option for attribute (existing option)
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@" with body:
      """
      {
        "code": "option_2",
        "label":  {
          "pl": "Option pl 3",
          "en": "Option en 3"
        }
      }
      """
    Then the response status code should be 400
  Scenario: Get created multi select
    And I send a "GET" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@"
    Then the response status code should be 200
    And the JSON node "label.pl" should contain "Option pl 3"
    And the JSON node "label.en" should contain "Option en 3"
    And the JSON node "code" should contain "option_3"

  Scenario: Delete option (not existing)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@/options/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete option
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@/options/@option_id@"
    Then the response status code should be 204

  Scenario: Delete multi select attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 204
