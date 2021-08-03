Feature: Product collection adding elements by skus

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_id"

  Scenario: Create product
    Given remember param "product_sku" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Get product up-sell collection type
    When I send a GET request to "/api/v1/en_GB/collections/type?field=code&filter=code=up-sell"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_collection_type_id"

  Scenario: Create product collection
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "typeId": "@product_collection_type_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_id"

  Scenario: Add product collection element by segments
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-skus" with body:
      """
      {
        "skus": ["@product_sku@"]
      }
      """
    Then the response status code should be 201

  Scenario: Get product collection element (checking multiple add)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_id@/elements"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | collection[0].sku     | @product_sku@ |
      | collection[0].id      | @product_id@  |
      | collection[0].visible | true          |

  Scenario: Add multiple product collection element (both empty fields)
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-skus" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Add multiple product collection element not valid sku
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-skus" with body:
      """
      {
        "skus": ["abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghij"]
      }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.skus.element-0[0] | Sku is too long. It should contain 255 characters or less. |

  Scenario: Add multiple product collection element not exist sku
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-skus" with body:
      """
      {
        "skus": ["@@random_code@@"]
      }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.skus.element-0[0] | Product sku not exists. |
