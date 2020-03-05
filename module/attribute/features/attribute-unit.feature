Feature: Unit attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create unit attribute
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "UNIT_@@random_code@@",
          "type": "UNIT",
          "groups": [],
          "parameters": {"unit": "M"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create unit attribute without required unit parameter
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "UNIT_@@random_code@@",
          "groups": []
      }
      """
    Then the response status code should be 400

  Scenario: Create unit attribute with invalid unit parameter
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "UNIT_@@random_code@@",
          "groups": [],
          "parameters": {"unit": "bac parameter"}
      }
      """
    Then the response status code should be 400

  Scenario: Update unit attribute
    And I send a "PUT" request to "/api/v1/EN/attributes/@attribute_id@" with body:
      """
      {
          "groups": [],
          "parameters": {"unit": "M"}
      }
      """
    Then the response status code should be 204

  Scenario: Get unit attribute
    And I send a "GET" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 200

  Scenario: Delete unit attribute
    And I send a "DELETE" request to "/api/v1/EN/attributes/@attribute_id@"
    Then the response status code should be 204