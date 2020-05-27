Feature: Product collection module

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "label": {"pl_PL": "Atrybut tekstowy", "en": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_attribute"

  Scenario: Create template
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": [
          {
            "position": {"x": 0, "y": 0},
            "size": {"width": 2, "height": 1},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "@product_template_attribute@",
              "required": true
            }
          }
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template"

  Scenario: Create category
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"de": "Test de", "en": "Test en"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_category"

  Scenario: Create product
    Given remember param "product_1_sku" with value "SKU_@@random_code@@"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "@product_1_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_1"

  Scenario: Create product
    Given remember param "product_2_sku" with value "SKU_@@random_code@@"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "@product_2_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_2"

  Scenario: Create product for segment 1
    Given remember param "segment_product_1_sku" with value "SEGMENT_SKU_@@random_code@@"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "@segment_product_1_sku@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_product_1"

  Scenario: Create condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/en/conditionsets" with body:
      """
     {"conditions":[{"type":"PRODUCT_SKU_EXISTS_CONDITION","operator":"=","value":"@segment_product_1_sku@"}]}
      """
    Then the response status code should be 201
    And store response param "id" as "segment_conditionset"

  Scenario: Create segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/segments" with body:
      """
      {
        "code": "SEG_1_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "pl_PL": "Segment",
          "en": "Segment"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "en": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment"

  Scenario: Create product collection type (not authorized)
    When I send a POST request to "/api/v1/en/collections/type"
    Then the response status code should be 401

  Scenario: Create first product collection type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/type" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de": "Name de",
                 "en": "Name en"
                 }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type_1"

  Scenario: Create second product collection type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/type" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de": "Name de",
                 "en": "Name en"
                 }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type_2"

  Scenario: Create second product collection type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/type" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de": "Name de",
                 "en": "Name en"
                 }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type_3"

  Scenario: Create product collection type (only code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/type" with body:
      """
      {
           "code": "TEXT_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Create product collection type (wrong not correct code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/type" with body:
      """
      {
           "code": "TEXT/. .,.]_@@random_code@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection type (wrong not correct name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/type" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "en": "Name en"
                 }
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection type (not authorized)
    When I send a PUT request to "/api/v1/en/collections/type/@product_collection_type_1@"
    Then the response status code should be 401

  Scenario: Update product collection type (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/type/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection type (no content)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/type/@product_collection_type_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/type/@product_collection_type_1@" with body:
      """
      {
          "name": {
                 "de": "Name de",
                 "en": "New Name en"
                 }
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection type (not authorized)
    When I send a PUT request to "/api/v1/en/collections/type/@product_collection_type_1@"
    Then the response status code should be 401

  Scenario: Request product collection type (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type/@product_collection_type_1@"
    Then the response status code should be 200
    And the JSON node "name.en" should be equal to the string "New Name en"

  Scenario: Get product collection type (not authorized)
    When I send a GET request to "/api/v1/en/collections/type"
    Then the response status code should be 401

  Scenario: Get product collection type (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection type (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection type (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?limit=25&offset=0&filter=code=text_"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection type (filter by null code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?limit=25&offset=0&filter=code="
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get product collection type (not authorized)
    When I send a GET request to "/api/v1/en/collections/type"
    Then the response status code should be 401

  Scenario: Get product collection type (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?limit=25&offset=0&filter=name=Name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection type (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection type  (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type?limit=50&offset=0&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete product collection type (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/collections/type/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection type (not authorized)
    When I send a DELETE request to "/api/v1/en/collections/type/@product_collection_type_2@"
    Then the response status code should be 401

  Scenario: Delete product collection type
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/collections/type/@product_collection_type_2@"
    Then the response status code should be 204

  Scenario: Request product collection type after deletion
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/type/@product_collection_type_2@"
    Then the response status code should be 404

  Scenario: Create product collection (not authorized)
    When I send a POST request to "/api/v1/en/collections"
    Then the response status code should be 401

  Scenario: Create first product collection
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
          "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_1"

  Scenario: Create second product collection
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
          "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_2"

  Scenario: Create third product collection
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
          "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_3"

  Scenario: Create product collection (no name and desc)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 201

  Scenario: Create product collection (wrong code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
          "code": "TEXT_//?$@@random_code@@",
          "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (wrong not correct name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "de": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "en": "Name en"
                 },
              "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (wrong not correct description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections" with body:
      """
      {
           "code": "TEXT_@@random_code@@",
              "description": {
                 "de": "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",
                 "en": "Description en"
                 },
              "typeId": "@product_collection_type_1@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection (not existing uuid)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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

  Scenario: Update product collection (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@"
    Then the response status code should be 401

  Scenario: Update product collection (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection (no content)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
          "typeId": "@product_collection_type_3@"
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@"
    Then the response status code should be 401

  Scenario: Request product collection (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@"
    Then the response status code should be 200
    And the JSON node "name.en" should be equal to the string "New Name en"
    And the JSON node "description.en" should be equal to the string "New Description en"

  Scenario: Get product collection (not authorized)
    When I send a GET request to "/api/v1/en/collections"
    Then the response status code should be 401

  Scenario: Get product collection (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (order by type_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?field=type_id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=code=text_"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection (filter by null code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=code="
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get product collection (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=name=Name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"


  Scenario: Get product collection (filter by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?limit=25&offset=0&filter=description=Description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection  (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection (not authorized)
    When I send a GET request to "/api/v1/en/collections"
    Then the response status code should be 401

  Scenario: Delete product collection (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection (not authorized)
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_2@"
    Then the response status code should be 401

  Scenario: Delete product collection
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_2@"
    Then the response status code should be 204

  Scenario: Request product collection after deletion
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_2@"
    Then the response status code should be 404

  Scenario: Create product collection element (not authorized)
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements"
    Then the response status code should be 401

  Scenario: Add product collection element
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@product_1@",
          "visible": true
      }
      """
    Then the response status code should be 201

  Scenario: Add product collection element (wrong product not uuid)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@@random_code@@",
          "visible": true
      }
      """
    Then the response status code should be 400

  Scenario: Add product collection element (wrong product doesn't exist)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements" with body:
      """
      {
          "productId": "@@static_uuid@@",
          "visible": true
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection element (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 401

  Scenario: Update product collection element (not found product)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection element (not found collection)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@/elements/@product_1@"
    Then the response status code should be 404

  Scenario: Update product collection element (no content)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection element
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@" with body:
      """
      {
        "visible": false
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection element (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 401

  Scenario: Request product collection element (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection element
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 200
    And the JSON node "visible" should be false

  Scenario: Get product collection element (not authorized)
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements"
    Then the response status code should be 401

  Scenario: Get product collection element (order by visible)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?field=visible"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[0].visible" should exist

  Scenario: Get product collection element (order by product_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?field=product_id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[1].id" should be equal to "system_name"

  Scenario: Get product collection element (order by product_collection_id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?field=product_collection_id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "columns[1].id" should be equal to "system_name"

  Scenario: Get product collection element (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?limit=25&offset=0&filter=visible=true"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection element (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection element  (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements?limit=50&offset=0&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete product collection element (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_1@/elements/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection element (not authorized)
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 401

  Scenario: Delete product collection element
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 204


  Scenario: Add multiple product collection element
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_3@/elements/multiple" with body:
      """
      {
        "segments": [
          "@segment@"
        ],
        "skus": "@product_1_sku@ , @product_2_sku@"
      }
      """
    Then the response status code should be 201

  Scenario: Get product collection element (checking multiple add)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections?filter=id=@product_collection_3@"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "collection[0].elements_count" should match "/3/"

  Scenario: Get products collection element  (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@segment_product_1@"
    Then the response status code should be 200

  Scenario: Get products collection element  (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@product_1@"
    Then the response status code should be 200

  Scenario: Get products collection element  (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/collections/@product_collection_1@/elements/@product_2@"
    Then the response status code should be 200

  Scenario: Add multiple product collection element (both empty fields)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements/multiple" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Add multiple product collection element (wrong segment)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements/multiple" with body:
      """
      {
            "segments": [
              "@@random_uuid@@"
            ]
      }
      """
    Then the response status code should be 400

  Scenario: Add multiple product collection element (wrong segment not uuid)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements/multiple" with body:
      """
      {
            "segments": [
              "@@random_code@@"
            ]
      }
      """
    Then the response status code should be 400

  Scenario: Add multiple product collection element (wrong skus)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements/multiple" with body:
      """
      {
            "skus": "@@random_code@@ , @@random_code@@"
      }
      """
    Then the response status code should be 400


  Scenario: Add multiple product collection element (wrong parameter name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/collections/@product_collection_1@/elements/multiple" with body:
      """
      {
            "sfesfeskus": "@product_1_sku@"
      }
      """
    Then the response status code should be 400
