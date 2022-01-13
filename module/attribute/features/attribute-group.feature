Feature: Attribute module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: Create attribute group <number>
    And I send a "POST" request to "/api/v1/en_GB/attributes/groups" with body:
      """
      {
        "code": "attribute_group_@@random_code@@",
        "name": {
          "pl_PL": "Grupa atrybutów numer <number>",
          "en_GB": "Attribute group number <number>"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<id>"
    Examples:
      | number | id                   |
      | 1      | attribute_group_id_1 |
      | 2      | attribute_group_id_2 |

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.pl_PL | Grupa atrybutów numer 1  |
      | name.en_GB | Attribute group number 1 |
    And store response param "code" as "exists_attribute_code"

  Scenario Outline: Create text attribute with invalid group (<value> - <message>)
    And I send a "POST" request to "/api/v1/en_GB/attributes/groups" with body:
      """
     {
        "code": <value>,
        "name": {
          "en_GB": "Attribute group name"
        }
      }
      """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | errors.code[0] | <message> |
    Examples:
      | value                     | message                                                        |
      | null                      | System name is required                                        |
      | ""                        | System name is required                                        |
      | "invald_code-%$#@!"       | System name can have only letters, digits or underscore symbol. |
      | "@exists_attribute_code@" | The value is not unique.                                       |

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.pl_PL | Grupa atrybutów numer 1  |
      | name.en_GB | Attribute group number 1 |

  Scenario: Get attributes groups
    And I send a "GET" request to "/api/v1/en_GB/attributes/groups"
    Then the response status code should be 200
    And the JSON node "collection" should not be null

  Scenario: Update attribute group (not found)
    And I send a "PUT" request to "/api/v1/en_GB/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Update attribute group
    And I send a "PUT" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_1@" with body:
      """
      {
        "name": {
          "pl_PL": "pl_PL",
          "en_GB": "en_GB"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.pl_PL | pl_PL |
      | name.en_GB | en_GB |

  Scenario: Ger attribute group (not found)
    And I send a "GET" request to "/api/v1/en_GB/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Create text attribute with group
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": ["@attribute_group_id_1@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario Outline: Create text attribute with invalid group (<value> - <message>)
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": [<value>]
      }
      """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | errors.groups.element-0[0] | <message> |
    Examples:
      | value             | message                         |
      | null              | This value should not be blank. |
      | ""                | This value should not be blank. |
      | "Not uuid"        | This is not a valid UUID.       |
      | "@@random_uuid@@" | Attribute group not exists.     |

  Scenario: Delete attribute group
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_1@"
    Then the response status code should be 409

  Scenario: Update attribute - remove group
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 204

  Scenario: Delete attribute group
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_1@"
    Then the response status code should be 204

  Scenario: Delete attribute group
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/groups/@attribute_group_id_2@"
    Then the response status code should be 204

  Scenario: Delete attribute group (not found)
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Delete attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
