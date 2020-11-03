Feature: Product collection

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Create product collection type
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "name": {
          "de_DE": "Name de",
          "en_GB": "Name en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type"

  Scenario: Create first product collection
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "de_DE": "Name de",
             "en_GB": "Name en"
          },
          "description": {
            "de_DE": "Description de",
            "en_GB": "Description en"
          },
          "typeId": "@product_collection_type@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection"

  Scenario: Add product collection element
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection@/elements" with body:
      """
      {
          "productId": "@product_id@",
          "visible": true
      }
      """
    Then the response status code should be 201

  Scenario Outline: Get product collection element filtered by <column> with value <filter>
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/collections?filter=<column>=<filter>"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].<column>" should contain "<filter>"
    Examples:
      | column      | filter      |
      | code        | TEXT        |
      | name        | Name        |
      | description | Description |

  Scenario: Delete product (product in collection)
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 409