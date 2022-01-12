Feature: Product collection module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_id"

  Scenario: Create product
    Given remember param "product_1_sku" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_1_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_1"

  Scenario: Create product
    Given remember param "product_2_sku" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_2_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_2"

  Scenario: Get product up-sell collection type
    When I send a GET request to "/api/v1/en_GB/collections/type?field=code&filter=code=up-sell"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_collection_type_1_id"

  Scenario: Get product up-sell collection type
    When I send a GET request to "/api/v1/en_GB/collections/type?field=code&filter=code=cross-sell"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_collection_type_2_id"

  Scenario Outline: Create first product collection
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
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "<property>"
    Examples:
      | property             |
      | product_collection_1 |
      | product_collection_2 |
      | product_collection_3 |

  Scenario: Create product collection (no name and desc)
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 201

  Scenario: Create product collection (wrong code)
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
          "code": "TEXT_//?$@@random_code@@",
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (wrong not correct name)
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de_DE": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "en_GB": "Name en"
                 },
              "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 400

  Scenario Outline: Create product collection with incorrect type ID (<value> - <message>)
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "description": {
          "en_GB": "Description en"
        },
        "typeId": <value>
      }
      """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | errors.typeId[0] | <message> |
    Examples:
      | value             | message                                      |
      | null              | Collection type id is required               |
      | ""                | Collection type id is required               |
      | "@random_code@"   | Collection type id must be valid uuid format |
      | "@@random_uuid@@" | Product collection type not exists.          |

  Scenario Outline: Create product collection type (<value> - <message>)
    When I send a POST request to "/api/v1/en_GB/collections" with body:
      """
      {
        "code": <value>,
        "description": {
          "en_GB": "Description en"
        },
        "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 400
    And the JSON nodes should be equal to:
      | errors.code[0] | <message> |
    Examples:
      | value                                                                    | message                                                                    |
      | null                                                                     | System name is required                                   |
      | ""                                                                       | System name is required                                   |
      | "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii" | System name is too long. It should contain 64 characters or less.          |
      | "TEXT/. .,.]"                                                            | Product collection system name can have only letters, digits or underscore symbol. |


  Scenario: Update product collection (not found)
    When I send a PUT request to "/api/v1/en_GB/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection (no content)
    When I send a PUT request to "/api/v1/en_GB/collections/@product_collection_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection
    When I send a PUT request to "/api/v1/en_GB/collections/@product_collection_1@" with body:
      """
      {
        "name": {
          "de_DE": "Name de",
          "en_GB": "New Name en"
        },
        "description": {
          "de_DE": "Description de",
          "en_GB": "New Description en"
        },
        "typeId": "@product_collection_type_2_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection (not found)
    When I send a GET request to "/api/v1/en_GB/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@"
    Then the response status code should be 200
    And the JSON node "name.en_GB" should be equal to the string "New Name en"
    And the JSON node "description.en_GB" should be equal to the string "New Description en"

  Scenario: Delete product collection (not found)
    When I send a DELETE request to "/api/v1/en_GB/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection
    When I send a DELETE request to "/api/v1/en_GB/collections/@product_collection_2@"
    Then the response status code should be 204

  Scenario: Request product collection after deletion
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_2@"
    Then the response status code should be 404

  Scenario: Add product collection element
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@product_1@",
          "visible": true
      }
      """
    Then the response status code should be 201

  Scenario: Add product collection element (wrong product not uuid)
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@@random_code@@",
          "visible": true
      }
      """
    Then the response status code should be 400

  Scenario: Add product collection element (wrong product doesn't exist)
    When I send a POST request to "/api/v1/en_GB/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@@static_uuid@@",
          "visible": true
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection element (not found product)
    When I send a PUT request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection element (not found collection)
    When I send a PUT request to "/api/v1/en_GB/collections/@@static_uuid@@/elements/@product_1@"
    Then the response status code should be 404

  Scenario: Update product collection element (no content)
    When I send a PUT request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@product_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection element
    When I send a PUT request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@product_1@" with body:
      """
      {
        "visible": false
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection element (not found)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection element
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 200
    And the JSON node "visible" should be false

  Scenario: Get product collection element (order by visible)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements?field=visible"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[0].visible" should exist

  Scenario: Get product collection element (order by product_id)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements?field=product_id"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[1].id" should be equal to "default_label"

  Scenario: Get product collection element (order by product_collection_id)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements?field=product_collection_id"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[1].id" should be equal to "default_label"

  Scenario: Get product collection element (filter by code)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements?limit=25&offset=0&filter=visible=true"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection element (order ASC)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection element  (order DESC)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements?limit=50&offset=0&order=DESC"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete product collection element (not found)
    When I send a DELETE request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection element
    When I send a DELETE request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 204

  Scenario: Get products collection element  (order DESC)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 200

  Scenario: Get products collection element  (order DESC)
    When I send a GET request to "/api/v1/en_GB/collections/@product_collection_1@/elements/@product_2@"
    Then the response status code should be 200


