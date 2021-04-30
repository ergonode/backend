Feature: Product Relation attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get attribute types dictionary
    And I send a "GET" request to "/api/v1/en_GB/dictionary/attributes/types"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | PRODUCT_RELATION | Product relations |

  Scenario: Create product relation attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "PRODUCT_RELATION_@@random_code@@",
          "type": "PRODUCT_RELATION",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Update product relation attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
        "type": "PRODUCT_RELATION",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 204

  Scenario: Get product relation attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id    | @attribute_id@   |
      | type  | PRODUCT_RELATION |
      | scope | local            |

  Scenario: Delete product relation attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
