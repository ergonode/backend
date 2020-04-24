Feature: Attribute module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create attribute group
    And I send a "POST" request to "/api/v1/en/attributes/groups" with body:
      """
      {
        "code": "ATTRIBUTE_GROUP_@@random_code@@",
        "name": {
          "pl_PL": "Grupa atrybutów pl",
          "en": "Attribute group en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_group_id1"

  Scenario: Create attribute group 2
    And I send a "POST" request to "/api/v1/en/attributes/groups" with body:
      """
      {
        "code": "ATTRIBUTE_GROUP_@@random_code@@",
        "name": {
          "pl_PL": "Nowa Grupa atrybutów pl",
          "en": "New Attribute group en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_group_id2"

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/en/attributes/groups/@attribute_group_id1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.pl_PL | Grupa atrybutów pl |
      | name.en | Attribute group en |

  Scenario: Get attributes groups
    And I send a "GET" request to "/api/v1/en/attributes/groups"
    Then the response status code should be 200
    And the JSON node "collection" should not be null

  Scenario: Update attribute group (not found)
    And I send a "PUT" request to "/api/v1/en/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Update attribute group
    And I send a "PUT" request to "/api/v1/en/attributes/groups/@attribute_group_id1@" with body:
      """
      {
        "name": {
          "pl_PL": "pl_PL",
          "en": "en"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/en/attributes/groups/@attribute_group_id1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.pl_PL | pl_PL |
      | name.en | en |

  Scenario: Ger attribute group (not found)
    And I send a "GET" request to "/api/v1/en/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Create text attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"pl_PL": "Atrybut tekstowy", "en": "Text attribute"},
          "groups": ["@attribute_group_id1@"],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Delete attribute group
    And I send a "DELETE" request to "/api/v1/en/attributes/groups/@attribute_group_id1@"
    Then the response status code should be 409

  Scenario: Delete attribute group
    And I send a "DELETE" request to "/api/v1/en/attributes/groups/@attribute_group_id2@"
    Then the response status code should be 204

  Scenario: Delete attribute group (not found)
    And I send a "DELETE" request to "/api/v1/en/attributes/groups/@static_uuid@"
    Then the response status code should be 404
