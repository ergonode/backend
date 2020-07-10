Feature: Product edit feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template
    When I send a POST request to "/api/v1/en/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template"

  Scenario: Create product
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Create category
    When I send a POST request to "/api/v1/en/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"en": "Test Category 1"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category1"

  Scenario: Create category
    When I send a POST request to "/api/v1/en/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"en": "Test Category 2"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category2"

  Scenario: Add Category1 to product
    When I send a POST request to "/api/v1/en/products/@product@/category" with body:
      """
      {
        "category": "@category1@"
      }
      """
    Then the response status code should be 204

  Scenario: Add Category2 to product
    When I send a POST request to "/api/v1/en/products/@product@/category" with body:
      """
      {
        "category": "@category2@"
      }
      """
    Then the response status code should be 204

  Scenario: Get product category grid
    When I send a GET request to "/api/v1/en/products/@product@/category"
    Then the response status code should be 200

  Scenario: Remove Category2 from product
    When I send a DELETE request to "/api/v1/en/products/@product@/category" with body:
      """
      {
        "category": "@category2@"
      }
      """
    Then the response status code should be 204
