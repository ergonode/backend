Feature: Text-area attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create textarea attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_AREA_@@random_code@@",
          "type": "TEXT_AREA",
          "groups": [],
          "scope": "local",
          "parameters":
          {
          "richEdit": true
          }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Get created textarea attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                  | @attribute_id@ |
      | type                | TEXT_AREA          |
      | scope               | local          |
      | parameters.rich_edit| true            |

  Scenario: Update textarea attribute first time
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
          "groups": [],
          "scope": "local",
          "parameters":
           {
          "richEdit": false
          }
      }
      """
    Then the response status code should be 204

  Scenario: Get created textarea attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                    | @attribute_id@ |
      | type                  | TEXT_AREA      |
      | scope                 | local          |
      | parameters.rich_edit |                 |

  Scenario: Update textarea attribute second time
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
          "groups": [],
          "scope": "local",
          "parameters":
           {
          "richEdit": true
          }
      }
      """
    Then the response status code should be 204

  Scenario: Get created textarea attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id                    | @attribute_id@ |
      | type                  | TEXT_AREA      |
      | scope                 | local          |
      | parameters.rich_edit | true           |

  Scenario: Create textarea attribute with invalid format parameter
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_AREA_@@random_code@@",
          "type": "TEXT_AREA",
          "groups": [],
          "scope": "local",
          "parameters":
          {
          "richEdit": "test"
          }
      }
      """
    Then the response status code should be 400

  Scenario: Delete textarea attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
