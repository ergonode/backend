Feature: Product module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "label": {"pl_PL": "Atrybut tekstowy", "en_GB": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_attribute"

  Scenario: Create template 1
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_1"

  Scenario: Create template 2
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_2"

  Scenario: Create category
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"de_DE": "Test de", "en_GB": "Test en"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_category"

  Scenario: Create category
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"de_DE": "Test de", "en_GB": "Test en"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_category_2"

  Scenario Outline: Create product with invalid <sku> SKU
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": <sku>,
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@",
        "categoryIds": []
      }
      """
    Then the response status code should be 400
    Examples:
      | sku                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
      | ""                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
      | "      "                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
      | "test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test testtest test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test" |

  Scenario: Get product type dictionary
    When I send a GET request to "/api/v1/en_GB/dictionary/product-type"
    Then the response status code should be 200
    And the JSON node "SIMPLE-PRODUCT" should exist

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Create product 2
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_2"

  Scenario: Create product with 255 char long sku
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_3"

  Scenario: Add children product to simple product
    When I send a POST request to "/api/v1/en_GB/products/@product@/children" with body:
      """
      {
        "child_id": "@product_2@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product (wrong product_template no UUID)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "test",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 400

  Scenario: Create product (wrong product_template wrong UUID)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@@random_uuid@@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 400

  Scenario: Create product (no templateId)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 400

  Scenario: Create product (empty categoryIds)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201

  Scenario: Create product (no categoryIds)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@"
      }
      """
    Then the response status code should be 201

  Scenario: Create product (categoryIds not UUID)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@",
        "categoryIds": ["test"]
      }
      """
    Then the response status code should be 400

  Scenario: Create product (no categoryIds)
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_1@"
      }
      """
    Then the response status code should be 201

  Scenario: Update product with new template
    When I send a PUT request to "/api/v1/en_GB/products/@product_2@" with body:
      """
      {
        "templateId": "@product_template_2@",
        "categoryIds": []
      }
      """
    Then the response status code should be 204

  Scenario: Get updated product new template
    When I send a GET request to "/api/v1/en_GB/products/@product_2@"
    Then the response status code should be 200
    And the JSON node "design_template_id" should be equal to "@product_template_2@"

  Scenario: Update product two categories
    When I send a PUT request to "/api/v1/en_GB/products/@product_2@" with body:
      """
      {
        "templateId": "@product_template_1@",
        "categoryIds": ["@product_category@", "@product_category_2@"]
      }
      """
    Then the response status code should be 204

  Scenario: Get updated product with 2 categories
    When I send a GET request to "/api/v1/en_GB/products/@product_2@"
    Then the response status code should be 200
    And the JSON node "categories[0]" should be equal to "@product_category@"
    And the JSON node "categories[1]" should be equal to "@product_category_2@"

  Scenario: Update product 1 once category
    When I send a PUT request to "/api/v1/en_GB/products/@product_2@" with body:
      """
      {
        "templateId": "@product_template_1@",
        "categoryIds": ["@product_category_2@"]
      }
      """
    Then the response status code should be 204

  Scenario: Get updated product with 2 categories
    When I send a GET request to "/api/v1/en_GB/products/@product_2@"
    Then the response status code should be 200
    And the JSON node "categories[0]" should be equal to "@product_category_2@"
    And the JSON node "categories[1]" should not exist

  Scenario: Update product (not found)
    When I send a PUT request to "/api/v1/en_GB/products/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product (no content)
    When I send a PUT request to "/api/v1/en_GB/products/@product@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product (categoryID not UUID)
    When I send a PUT request to "/api/v1/en_GB/products/@product@" with body:
      """
      {
        "categoryIds": ["@@random_md5@@"]
      }
      """
    Then the response status code should be 400

  Scenario: Update product (categoryID wrong UUID)
    When I send a PUT request to "/api/v1/en_GB/products/@product@" with body:
      """
      {
        "categoryIds": ["@@random_uuid@@"]
      }
      """
    Then the response status code should be 400

  Scenario: Get product
    When I send a GET request to "/api/v1/en_GB/products/@product@"
    Then the response status code should be 200

  Scenario: Get product (not found)
    When I send a GET request to "/api/v1/en_GB/products/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product (not found)
    When I send a DELETE request to "/api/v1/en_GB/products/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product
    When I send a DELETE request to "/api/v1/en_GB/products/@product_2@"
    Then the response status code should be 204

  Scenario: Get products (order by id)
    When I send a GET request to "/api/v1/en_GB/products?field=id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (order by index)
    When I send a GET request to "/api/v1/en_GB/products?field=index"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (order by sku)
    When I send a GET request to "/api/v1/en_GB/products?field=sku"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (order ASC)
    When I send a GET request to "/api/v1/en_GB/products?field=index&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (order DESC)
    When I send a GET request to "/api/v1/en_GB/products?field=index&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (filter by index)
    When I send a GET request to "/api/v1/en_GB/products?limit=25&offset=0&filter=index%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (filter by id)
    When I send a GET request to "/api/v1/en_GB/products?limit=25&offset=0&filter=id%3DCAT"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products (filter by sku)
    When I send a GET request to "/api/v1/en_GB/products?limit=25&offset=0&filter=sku%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
