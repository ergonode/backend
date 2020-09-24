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

  Scenario: Create second simple product
    Given remember param "second_simple_product_sku" with value "SIMPLE_SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@second_simple_product_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "second_simple_product_id"

  Scenario: Create grouping product
    Given remember param "grouping_product_sku" with value "GROUPING_SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@grouping_product_sku@",
        "type": "GROUPING-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "grouping_product_id"

  Scenario: Create variable product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "VARIABLE-PRODUCT",
        "templateId": "@product_template_id@",
        "bindings": [
          "@attribute_id@"
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Get binded attributes
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/bindings"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | [0] | @attribute_id@ |

  Scenario: Get created grouping product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "type" should be equal to "VARIABLE-PRODUCT"
    And the JSON node "id" should be equal to "@product_id@"

  Scenario: Add product children by skus
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children/add-from-skus" with body:
      """
      {
        "skus": [
          "@simple_product_sku@",
          "@second_simple_product_sku@"
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Add not exists children
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children/add-from-skus" with body:
      """
      {
        "skus": [
          "not _exists"
        ]
      }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.skus.element-0[0] | Product sku not exists. |

  Scenario: Get product children element (checking multiple add)
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/children"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | collection[0].sku     | @simple_product_sku@ |
      | collection[0].id      | @simple_product_id@  |
      | collection[1].sku     | @second_simple_product_sku@ |
      | collection[1].id      | @second_simple_product_id@  |
