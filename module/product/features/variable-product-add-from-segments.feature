Feature: Variable product

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get product type dictionary
    When I send a GET request to "/api/v1/en_GB/dictionary/product-type"
    Then the response status code should be 200
    And the JSON node "VARIABLE-PRODUCT" should exist

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id"

  Scenario: Create select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "SELECT_BIND_@@random_code@@",
          "type": "SELECT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create simple product
    Given remember param "simple_product_sku" with value "SIMPLE_SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@simple_product_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "simple_product_id"

  Scenario: Create variable product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "VARIABLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Add bind attribute
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/binding" with body:
      """
      {
        "bind_id": "@attribute_id@"
      }
      """
    Then the response status code should be 201

  Scenario: Get binded attributes
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/bindings"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | [0] | @attribute_id@ |

  Scenario: Create condition set
    Given I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
      {
        "conditions":[
          {
            "type":"PRODUCT_SKU_EXISTS_CONDITION",
            "operator":"=",
            "value":"@simple_product_sku@"
          }
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_set_id"

  Scenario: Create segment
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_1_@@random_code@@",
        "condition_set_id": "@condition_set_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_id"

  Scenario: Get created grouping product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "type" should be equal to "VARIABLE-PRODUCT"
    And the JSON node "id" should be equal to "@product_id@"

  Scenario: Add product collection element by segments
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children/add-from-segments" with body:
      """
      {
        "segments": ["@segment_id@"]
      }
      """
    Then the response status code should be 204

# @todo require resolve problem of reading messages in test mode
#  Scenario: Get product children element (checking multiple add)
#    When I send a GET request to "/api/v1/en_GB/products/@product_id@/children"
#    Then the response status code should be 200
#    And the JSON nodes should be equal to:
#      | collection[0].sku     | @simple_product_sku@ |
#      | collection[0].id      | @simple_product_id@  |

