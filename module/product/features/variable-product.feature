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


  Scenario: Create workflow status
    And I send a "POST" request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0",
        "code": "ST @@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "workflow_status_1_id"

  Scenario: Set default status
    When I send a PUT request to "/api/v1/en_GB/workflow/default/status/@workflow_status_1_id@/default"
    Then the response status code should be 204

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

  Scenario: Create second select attribute
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
    And store response param "id" as "attribute_id_2"

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

  Scenario: Create simple product 1
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "simple_product_id_1"

  Scenario: Edit product select value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@simple_product_id_1@/attribute/@attribute_id@" with body:
      """
        {
          "value": "@option_id_1@"
        }
      """
    Then the response status code should be 200

  Scenario: Create simple product 2
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "simple_product_id_2"

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

  Scenario: Create second variable product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "VARIABLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "second_product_id"

  Scenario: Create variable product without template
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "VARIABLE-PRODUCT"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.templateId" should exist

  Scenario: Update variable product
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Update variable product without template
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@" with body:
      """
      {
        "templateId": null
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.templateId" should exist

  Scenario: Add children product with invalid uuid
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "bcd"
      }
      """
    Then the response status code should be 400

  Scenario: Add children product with not exists product
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario: Add children product without binding attribute
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Add bind attribute with invalid uuid
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/binding" with body:
      """
      {
        "bind_id": "abcd"
      }
      """
    Then the response status code should be 400

  Scenario: Add bind attribute with not exists uuid
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/binding" with body:
      """
      {
        "bind_id": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

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

  Scenario: Add children product
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id_1@"
      }
      """
    Then the response status code should be 204

  Scenario: Add bind attribute to product with children
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/binding" with body:
      """
      {
        "bind_id": "@attribute_id_2@"
      }
      """
    Then the response status code should be 400

  Scenario: Remove bind attribute from product with children
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/binding/@attribute_id@"
    Then the response status code should be 400

  Scenario: Add children product without correct binding attribute
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id_2@"
      }
      """
    Then the response status code should be 400


  Scenario: Add parent as children product
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "@product_id@"
      }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | code               | 400                           |
      | message            | Form validation error         |
      | errors.child_id[0] | Can't add parent as children. |
      | errors.child_id[1] | Product doesn't have required attribute. |

  Scenario: Add variable second as children product
    When I send a POST request to "/api/v1/en_GB/products/@product_id@/children" with body:
      """
      {
        "child_id": "@second_product_id@"
      }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | code               | 400                     |
      | message            | Form validation error   |
      | errors.child_id[0] | Product doesn't have required attribute. |
      | errors.child_id[1] | Incorrect product type. |

  Scenario: Request child grid filtered for given product
    When I send a GET request to "api/v1/en_GB/products/@product_id@/children"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @simple_product_id_1@ |
      | info.count       | 1                   |

   Scenario: Remove product which has parent product
     When I send a DELETE request to "/api/v1/en_GB/products/@simple_product_id_1@"
     Then the response status code should be 409

  Scenario: Remove children product
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/children/@simple_product_id_1@"
    Then the response status code should be 204

  Scenario: Remove product which is removed from parent
    When I send a DELETE request to "/api/v1/en_GB/products/@simple_product_id_1@"
    Then the response status code should be 204

  Scenario: Request child grid filtered for given product
    When I send a GET request to "api/v1/en_GB/products/@product_id@/children"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | info.count | 0 |

  Scenario: Get created grouping product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "type" should be equal to "VARIABLE-PRODUCT"
    And the JSON node "id" should be equal to "@product_id@"

  Scenario: Remove bind attribute
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/binding/@attribute_id@"
    Then the response status code should be 204

  Scenario: Remove bind attribute with not exist uuid
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/binding/@@random_uuid@@"
    Then the response status code should be 404

  Scenario: Get binded attributes
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/bindings"
    Then the response status code should be 200
    And the response should contain "[]"
