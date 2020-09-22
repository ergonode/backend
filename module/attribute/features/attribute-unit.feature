Feature: Unit attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create unit object 1
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "unit_id_1"

  Scenario: Create unit object 2
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "unit_id_2"

  Scenario: Create unit attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "UNIT_@@random_code@@",
          "type": "UNIT",
          "groups": [],
          "scope": "local",
          "parameters": {"unit": "@unit_id_1@"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Get created unit attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id              | @attribute_id@ |
      | type            | UNIT           |
      | scope           | local          |
      | parameters.unit | @unit_id_1@    |

  Scenario: Create unit attribute without required unit parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "UNIT_@@random_code@@",
          "type": "UNIT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 400

  Scenario: Create unit attribute with invalid unit parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "UNIT_@@random_code@@",
          "type": "UNIT",
          "scope": "local",
          "groups": [],
          "parameters": {"unit": "bac parameter"}
      }
      """
    Then the response status code should be 400

  Scenario: Update unit attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
          "groups": [],
          "scope": "local",
          "parameters": {"unit": "@unit_id_2@"}
      }
      """
    Then the response status code should be 204

  Scenario: Get unit attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200

  Scenario: Delete unit attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
