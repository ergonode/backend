Feature: Product collection module

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
    Given remember param "product_1_sku" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "@product_1_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_1"

  Scenario: Create product
    Given remember param "product_2_sku" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "@product_2_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_2"

  Scenario: Get product up-sell collection type
    When I send a GET request to "/api/v1/en/collections/type?field=code&filter=code=up-sell"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_collection_type_1_id"

  Scenario: Get product up-sell collection type
    When I send a GET request to "/api/v1/en/collections/type?field=code&filter=code=cross-sell"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_collection_type_2_id"

  Scenario: Create first product collection
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "de": "Name de",
             "en": "Name en"
          },
          "description": {
            "de": "Description de",
            "en": "Description en"
          },
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_1"

  Scenario: Create second product collection
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "de": "Name de",
             "en": "Name en"
          },
          "description": {
            "de": "Description de",
            "en": "Description en"
          },
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_2"

  Scenario: Create third product collection
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "de": "Name de",
             "en": "Name en"
          },
          "description": {
            "de": "Description de",
            "en": "Description en"
          },
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_3"

  Scenario: Create product collection (no name and desc)
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 201

  Scenario: Create product collection (wrong code)
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_//?$@@random_code@@",
          "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (wrong not correct name)
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "en": "Name en"
                 },
              "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (wrong not correct description)
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "description": {
                 "de": "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",
                 "en": "Description en"
                 },
              "typeId": "@product_collection_type_1_id@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (not existing uuid)
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "de": "Name de",
             "en": "Name en"
          },
          "description": {
            "de": "Description de",
            "en": "Description en"
          },
          "typeId": "@@static_uuid@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (not uuid)
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "de": "Name de",
             "en": "Name en"
          },
          "description": {
            "de": "Description de",
            "en": "Description en"
          },
          "typeId": "@@random_code@@"
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection (not found)
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection (no content)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@" with body:
      """
      {
        "name": {
          "de": "Name de",
          "en": "New Name en"
        },
        "description": {
          "de": "Description de",
          "en": "New Description en"
        },
        "typeId": "@product_collection_type_2_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection (not found)
    When I send a GET request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@"
    Then the response status code should be 200
    And the JSON node "name.en" should be equal to the string "New Name en"
    And the JSON node "description.en" should be equal to the string "New Description en"

  Scenario: Get product collection (order by code)
    When I send a GET request to "/api/v1/en/collections?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (order by name)
    When I send a GET request to "/api/v1/en/collections?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (order by description)
    When I send a GET request to "/api/v1/en/collections?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (order by type_id)
    When I send a GET request to "/api/v1/en/collections?field=type_id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (filter by code)
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=code=text_"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection (filter by null code)
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=code="
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get product collection (filter by name)
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=name=Name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection (filter by description)
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=description=Description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection (order ASC)
    When I send a GET request to "/api/v1/en/collections?order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection  (order DESC)
    When I send a GET request to "/api/v1/en/collections?order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete product collection (not found)
    When I send a DELETE request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_2@"
    Then the response status code should be 204

  Scenario: Request product collection after deletion
    When I send a GET request to "/api/v1/en/collections/@product_collection_2@"
    Then the response status code should be 404

  Scenario: Add product collection element
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@product_1@",
          "visible": true
      }
      """
    Then the response status code should be 201

  Scenario: Add product collection element (wrong product not uuid)
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@@random_code@@",
          "visible": true
      }
      """
    Then the response status code should be 400

  Scenario: Add product collection element (wrong product doesn't exist)
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@@static_uuid@@",
          "visible": true
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection element (not found product)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection element (not found collection)
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@/elements/@product_1@"
    Then the response status code should be 404

  Scenario: Update product collection element (no content)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection element
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@" with body:
      """
      {
        "visible": false
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection element (not found)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection element
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 200
    And the JSON node "visible" should be false

  Scenario: Get product collection element (order by visible)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?field=visible"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[0].visible" should exist

  Scenario: Get product collection element (order by product_id)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?field=product_id"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[1].id" should be equal to "system_name"

  Scenario: Get product collection element (order by product_collection_id)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?field=product_collection_id"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[1].id" should be equal to "system_name"

  Scenario: Get product collection element (filter by code)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?limit=25&offset=0&filter=visible=true"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection element (order ASC)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection element  (order DESC)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?limit=50&offset=0&order=DESC"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete product collection element (not found)
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection element
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 204

  Scenario: Get products collection element  (order DESC)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 200

  Scenario: Get products collection element  (order DESC)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@product_2@"
    Then the response status code should be 200


