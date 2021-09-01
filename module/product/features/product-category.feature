Feature: Product edit feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_template"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Get 1 category id
    When I send a GET request to "/api/v1/en_GB/categories?filter=name=Category_1&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "category1"

  Scenario: Get 1 category id
    When I send a GET request to "/api/v1/en_GB/categories?filter=name=Category_2&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "category2"

  Scenario: Add Category1 to product
    When I send a POST request to "/api/v1/en_GB/products/@product@/category" with body:
      """
      {
        "category": "@category1@"
      }
      """
    Then the response status code should be 204

  Scenario: Add Category2 to product
    When I send a POST request to "/api/v1/en_GB/products/@product@/category" with body:
      """
      {
        "category": "@category2@"
      }
      """
    Then the response status code should be 204

  Scenario: Get product category grid
    When I send a GET request to "/api/v1/en_GB/products/@product@/category"
    Then the response status code should be 200

  Scenario: Remove Category2 from product
    When I send a DELETE request to "/api/v1/en_GB/products/@product@/category" with body:
      """
      {
        "category": "@category2@"
      }
      """
    Then the response status code should be 204

  Scenario: Get product category grid
    When I send a GET request to "/api/v1/en_GB/products/@product@/category"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | collection[0].id   | @category1@ |
      | collection[0].code | category_1  |
      | collection[0].name | Category_1  |

  Scenario: Remove Category1 from product
    When I send a DELETE request to "/api/v1/en_GB/products/@product@/category" with body:
      """
      {
        "category": "@category1@"
      }
      """
    Then the response status code should be 204

  Scenario: Delete category
    When I send a DELETE request to "/api/v1/en_GB/categories/@category1@"
    Then the response status code should be 204
