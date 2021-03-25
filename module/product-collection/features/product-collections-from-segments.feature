Feature: Product collection adding elements by segments

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
    Given remember param "product_sku" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
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

  Scenario: Create condition set
    Given I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
      {
        "conditions":[
          {
            "type":"PRODUCT_SKU_EXISTS_CONDITION",
            "operator":"=",
            "value":"@product_sku@"
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

  Scenario: Add product collection element by segments
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-segments" with body:
      """
      {
        "segments": ["@segment_id@"]
      }
      """
    Then the response status code should be 201

# @todo require resolve problem of reading messages in test mode
#  Scenario: Get product collection element (checking multiple add)
#    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_id@/elements"
#    Then the response status code should be 200
#    And the JSON nodes should be equal to:
#      | collection[0].sku     | @product_sku@ |
#      | collection[0].id      | @product_id@  |
#      | collection[0].visible | true          |

  Scenario: Add multiple product collection element (both empty fields)
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-segments" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Add multiple product collection element (wrong segment)
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-segments" with body:
      """
      {
          "segments": ["@@random_uuid@@"]
      }
      """
    Then the response status code should be 400

  Scenario: Add multiple product collection element (wrong segment not uuid)
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_id@/elements/add-from-segments" with body:
      """
      {
            "segments": ["@@random_code@@"]
      }
      """
    Then the response status code should be 400
