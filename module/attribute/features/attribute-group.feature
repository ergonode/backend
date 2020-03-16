Feature: Attribute module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create attribute group
    And I send a "POST" request to "/api/v1/EN/attributes/groups" with body:
      """
      {
        "code": "ATTRIBUTE_GROUP_@@random_code@@",
        "name": {
          "PL": "Grupa atrybutów PL",
          "EN": "Attribute group EN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_group_id"

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/EN/attributes/groups/@attribute_group_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.PL | Grupa atrybutów PL |
      | name.EN | Attribute group EN |

  Scenario: Get attributes groups
    And I send a "GET" request to "/api/v1/EN/attributes/groups"
    Then the response status code should be 200
    And the JSON node "collection" should not be null

  Scenario: Update attribute group (not found)
    And I send a "PUT" request to "/api/v1/EN/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Update attribute group
    And I send a "PUT" request to "/api/v1/EN/attributes/groups/@attribute_group_id@" with body:
      """
      {
        "name": {
          "PL": "PL",
          "EN": "EN"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Get attribute group
    And I send a "GET" request to "/api/v1/EN/attributes/groups/@attribute_group_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | name.PL | PL |
      | name.EN | EN |

  Scenario: Ger attribute group (not found)
    And I send a "GET" request to "/api/v1/EN/attributes/groups/@static_uuid@"
    Then the response status code should be 404

  Scenario: Create text attribute
    And I send a "POST" request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": ["@attribute_group_id@"],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Delete attribute group
    And I send a "DELETE" request to "/api/v1/EN/attributes/groups/@attribute_group_id@"
    Then the response status code should be 204

  Scenario: Delete attribute group (not found)
    And I send a "DELETE" request to "/api/v1/EN/attributes/groups/@static_uuid@"
    Then the response status code should be 404
