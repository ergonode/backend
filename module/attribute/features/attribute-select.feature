Feature: Select attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "SELECT@@random_code@@",
          "type": "SELECT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create second select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "SELECT@@random_code@@",
          "type": "SELECT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_2_id"

  Scenario: Get created attribute
    When I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And store response param "code" as "attribute_code"

  Scenario: Create option without code
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": null,
        "label":  {
          "pl_PL": "Option pl 1",
          "en_GB": "Option en 1"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create option with not exists position id
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "valid-code",
        "label":  {
          "pl_PL": "Option pl 1",
          "en_GB": "Option en 1"
        },
        "positionId": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario Outline: Create option <code> for attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "<code>",
        "label":  {
          "pl_PL": "<pl>",
          "en_GB": "<en>"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<id>"
    Examples:
      | code     | pl          | en          | id          |
      | option_1 | Option pl 1 | Option en 1 | option_1_id |
      | option_2 | Option pl 2 | Option en 2 | option_2_id |

  Scenario: Create option option_3 for attribute after option_1
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "option_3",
        "label":  {
          "pl_PL": "Option pl 3",
          "en_GB": "Option en 3"
        },
        "positionId": "@option_1_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "option_3_id"

  Scenario: Create option option_4 for attribute before option_3
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "option_4",
        "label":  {
          "pl_PL": "Option pl 4",
          "en_GB": "Option en 4"
        },
        "after": false,
        "positionId": "@option_3_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "option_4_id"

  Scenario: Create option option_5 att beginning
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "option_5",
        "label":  {
          "pl_PL": "Option pl 5",
          "en_GB": "Option en 5"
        },
        "after": false
      }
      """
    Then the response status code should be 201
    And store response param "id" as "option_5_id"

  Scenario: Create option option for second attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_2_id@/options" with body:
      """
      {
        "code": "option_second",
        "label":  {
          "pl_PL": "Option pl second",
          "en_GB": "Option en second"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "option_second_id"

  Scenario: Create option after position from other attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "positionId": "@option_second_id@"
      }
      """
    Then the response status code should be 400

  Scenario: Create option for attribute (option already exists)
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "option_1",
        "label":  {
          "pl_PL": "Option pl 1",
          "en_GB": "Option en 1"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get created select
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_1_id@"
    Then the response status code should be 200
    And the JSON node "label.pl_PL" should contain "Option pl 1"
    And the JSON node "label.en_GB" should contain "Option en 1"
    And the JSON node "code" should contain "option_1"

  Scenario: Update option for attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_1_id@" with body:
      """
      {
        "code": "option_1_updated",
        "label":  {
          "pl_PL": "Option pl 1 updated",
          "en_GB": "Option en 1 updated"
        }
      }
      """
    Then the response status code should be 200

  Scenario: Update option for attribute (existing option)
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_1_id@" with body:
      """
      {
        "code": "option_2",
        "label":  {
          "pl_PL": "Option pl 3",
          "en_GB": "Option en 3"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get created option
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_1_id@"
    Then the response status code should be 200
    And the JSON node "label.pl_PL" should contain "Option pl 1 updated"
    And the JSON node "label.en_GB" should contain "Option en 1 updated"
    And the JSON node "code" should contain "option_1_updated"

  Scenario: Check attribute options sort after adding all
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@/options"
    Then the response status code should be 200
    And the JSON node "[0].id" should contain "@option_5_id@"
    And the JSON node "[1].id" should contain "@option_1_id@"
    And the JSON node "[2].id" should contain "@option_4_id@"
    And the JSON node "[3].id" should contain "@option_3_id@"
    And the JSON node "[4].id" should contain "@option_2_id@"

  Scenario: Move option with not exists position id
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_5_id@/move" with body:
      """
      {
        "positionId": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario: Move option after position from other attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_1_id@/move" with body:
      """
      {
        "positionId": "@option_second_id@"
      }
      """
    Then the response status code should be 400

  Scenario: Move option 5 after option 3
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_5_id@/move" with body:
      """
      {
        "positionId": "@option_3_id@"
      }
      """
    Then the response status code should be 200

  Scenario: Move option 3 to beginning
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_3_id@/move" with body:
      """
      {
        "after": false
      }
      """
    Then the response status code should be 200

  Scenario: Move option 1 to the end
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_1_id@/move" with body:
      """
      {
        "after": true
      }
      """
    Then the response status code should be 200

  Scenario: Move option 2 before option 5
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_2_id@/move" with body:
      """
      {
        "after": false,
        "positionId": "@option_5_id@"
      }
      """
    Then the response status code should be 200

  Scenario: Move option 5 to same position
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_5_id@/move" with body:
      """
      {
        "after": true,
        "positionId": "@option_2_id@"
      }
      """
    Then the response status code should be 200

  Scenario: Check attribute options sort after moving
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@/options"
    Then the response status code should be 200
    And the JSON node "[0].id" should contain "@option_3_id@"
    And the JSON node "[1].id" should contain "@option_4_id@"
    And the JSON node "[2].id" should contain "@option_2_id@"
    And the JSON node "[3].id" should contain "@option_5_id@"
    And the JSON node "[4].id" should contain "@option_1_id@"

  Scenario: Get attributes filter by attribute empty groups
    And I send a "GET" request to "/api/v1/en_GB/attributes?limit=25&offset=0&filter=code%3D@attribute_code@;groups="
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id   | @attribute_id@   |
      | collection[0].code | @attribute_code@ |
      | collection[0].type | SELECT           |

  Scenario: Delete option (not existing)
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Delete option
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_5_id@"
    Then the response status code should be 204

  Scenario: Delete option
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@/options/@option_4_id@"
    Then the response status code should be 204

  Scenario: Get attribute options
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@/options"
    Then the response status code should be 200
    And the JSON node "[0].id" should contain "@option_3_id@"
    And the JSON node "[1].id" should contain "@option_2_id@"
    And the JSON node "[2].id" should contain "@option_1_id@"

  Scenario: Check attribute options relations
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@/options"
    Then the response status code should be 200
    And the JSON node "[0].relations" should be false
    And the JSON node "[1].relations" should be false
    And the JSON node "[2].relations" should be false

  Scenario: Delete select attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
