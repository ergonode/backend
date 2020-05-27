Feature: Grouping product

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get product type dictionary
    When I send a GET request to "/api/v1/en/dictionary/product-type"
    Then the response status code should be 200
    And the JSON node "GROUPING-PRODUCT" should exist

  Scenario: Create template
    When I send a POST request to "/api/v1/en/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id"

  Scenario: Create simple product
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "simple_product_id"

  Scenario: Create grouping product
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "GROUPING-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Add children product with invalid uuid
    When I send a POST request to "/api/v1/en/products/@product_id@/children" with body:
      """
      {
        "child_id": "bcd"
      }
      """
    Then the response status code should be 400

  Scenario: Add children product with not exists product
    When I send a POST request to "/api/v1/en/products/@product_id@/children" with body:
      """
      {
        "child_id": "@@random_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario: Add children product
    When I send a POST request to "/api/v1/en/products/@product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Request child grid filtered for given product
    When I send a GET request to "api/v1/en/products/@product_id@/children"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @simple_product_id@ |
      | info.count       | 1                   |

  Scenario: Remove children product
    When I send a DELETE request to "/api/v1/en/products/@product_id@/children/@simple_product_id@"
    Then the response status code should be 204

  Scenario: Request child grid filtered for given product
    When I send a GET request to "api/v1/en/products/@product_id@/children"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | info.count | 0 |

  Scenario: Get created grouping product
    When I send a GET request to "/api/v1/en/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "type" should be equal to "GROUPING-PRODUCT"
    And the JSON node "id" should be equal to "@product_id@"