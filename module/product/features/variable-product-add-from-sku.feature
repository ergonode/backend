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

  Scenario: Create option for attribute
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
    Then the response status code should be 201
    And store response param "id" as "option_id_1"

  Scenario: Create second option for attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@attribute_id@/options" with body:
      """
      {
        "code": "option_2",
        "label":  {
          "pl_PL": "Option pl 2",
          "en_GB": "Option en 2"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "option_id_2"

  Scenario: Create simple product
    Given remember param "simple_product_sku" with value "SIMPLE_SKU_1_@@random_code@@"
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
    Given remember param "second_simple_product_sku" with value "SIMPLE_SKU_2_@@random_code@@"
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

  Scenario: Create third simple product
    Given remember param "third_simple_product_sku" with value "SIMPLE_SKU_3_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@third_simple_product_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201

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

  Scenario: Get created grouping product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "type" should be equal to "VARIABLE-PRODUCT"
    And the JSON node "id" should be equal to "@product_id@"

  Scenario: Edit product select value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@simple_product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": "@option_id_1@"
        }
      """
    Then the response status code should be 200

  Scenario: Edit second product select value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@second_simple_product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": "@option_id_2@"
        }
      """
    Then the response status code should be 200

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

  Scenario: Add product children without correct binding attribute by skus
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children/add-from-skus" with body:
      """
      {
        "skus": [
          "@third_simple_product_sku@"
        ]
      }
      """
    Then the response status code should be 400

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
      | errors.skus.element-0[0] | Product not _exists doesn't have required attribute. |

  Scenario: Get product children element (checking multiple add)
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/children?field=sku&order=ASC"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | collection[0].sku     | @simple_product_sku@ |
      | collection[0].id      | @simple_product_id@  |
      | collection[1].sku     | @second_simple_product_sku@ |
      | collection[1].id      | @second_simple_product_id@  |
