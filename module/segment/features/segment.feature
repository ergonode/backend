Feature: Segment module

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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

  Scenario: Create template
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/templates" with body:
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
    When I send a POST request to "/api/v1/en_GB/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"de_DE": "Test de", "en_GB": "Test en"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_category"

  Scenario: Create product
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_1"

  Scenario: Create select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "CONDITION_SELECT_@@random_code@@",
          "type": "SELECT",
          "scope": "global",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_attribute_id"

  Scenario: I add option to select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/api/v1/en_GB/attributes/@select_attribute_id@/options" with body:
      """
      {
          "code": "val_1"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_val_1_id"

  Scenario: I add second option to select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/api/v1/en_GB/attributes/@select_attribute_id@/options" with body:
      """
      {
          "code": "val_2"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_val_2_id"

  Scenario: Create OPTION_ATTRIBUTE_VALUE_CONDITION condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "OPTION_ATTRIBUTE_VALUE_CONDITION",
              "attribute": "@select_attribute_id@",
              "value": "@select_val_1_id@"
            }
          ]
        }
      """
    Then the response status code should be 201
    And store response param "id" as "condition_set_id"

  Scenario: Assign select attribute to product
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en_GB/products/@product_id_1@/draft/@select_attribute_id@/value" with body:
      """
      {
        "value": "@select_val_1_id@"
      }
      """
    Then the response status code should be 200

  Scenario: Apply product draft
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en_GB/products/@product_id_1@/draft/persist"
    Then the response status code should be 204

  Scenario: Create condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/en_GB/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_conditionset"

  Scenario: Create segment (not authorized)
    When I send a POST request to "/api/v1/en_GB/segments"
    Then the response status code should be 401

  Scenario: Create segment
    Given remember param "segment_code" with value "SEG_1_@@random_code@@"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "pl_PL": "Segment",
          "en_GB": "Segment"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment"

  Scenario: Create segment with select
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@condition_set_id@",
        "name": {
          "pl_PL": "Segment z opcją",
          "en_GB": "Segment with option"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_2"

  Scenario: Create segment (not unique code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create segment (too long code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "r6mph1idphxrfzmxfig8s4qkrjthwna3d5dhmd1zyhx2pgqind7uzm2z2o33unptminnrbtbel9a75xiqhxd2kusog1fi6g9t0tf1"
      }
      """
    Then the response status code should be 400

  Scenario: Create segment (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 201

  Scenario: Create segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_3_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "pl_PL": "Segment",
          "en_GB": "Segment"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_3"

  Scenario: Create segment (without description and name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@segment_conditionset@"
      }
      """
    Then the response status code should be 201

  Scenario: Create segment (without code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "pl_PL": "Segment",
          "en_GB": "Segment"
        },
        "description": {
          "pl_PL": "Opis segmentu",
          "en_GB": "Segment description"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create segment (without condition set)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/segments" with body:
      """
      {
        "code": "SEG_2_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Update segment (not authorized)
    When I send a PUT request to "/api/v1/en_GB/segments/@segment@"
    Then the response status code should be 401

  Scenario: Update segment (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/segments/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/segments/@segment@" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "pl_PL": "Segment (changed)",
          "en_GB": "Segment (changed)"
        },
        "description": {
          "pl_PL": "Opis segmentu (changed)",
          "en_GB": "Segment description (changed)"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update segment (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/segments/@segment@" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "pl_PL": "Opis segmentu (changed)",
          "en_GB": "Segment description (changed)"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update segment (without name and description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/segments/@segment@" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@"
      }
      """
    Then the response status code should be 204

  Scenario: Update segment (without condition set)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en_GB/segments/@segment@" with body:
      """
      {
        "name": {
          "pl_PL": "Segment (changed)",
          "en_GB": "Segment (changed)"
        },
        "description": {
          "pl_PL": "Opis segmentu (changed)",
          "en_GB": "Segment description (changed)"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Create product (matching existing segment)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_2"

  Scenario: Get segment (not authorized)
    When I send a GET request to "/api/v1/en_GB/segments/@segment@"
    Then the response status code should be 401

  Scenario: Get segment (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@segment@"
    Then the response status code should be 200

  Scenario: Get segments
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get segments (not authorized)
    When I send a GET request to "/api/v1/en_GB/segments"
    Then the response status code should be 401

  Scenario: Get segments (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments?field=code"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get segments (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments?field=name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get segments (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments?field=description"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get segments (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments?limit=25&offset=0&filter=code%3Dsuper"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get segments (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments?limit=25&offset=0&filter=name%3Dsuper"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get segments (filter by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments?limit=25&offset=0&filter=description%3Dsuper"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get products based on segment (not authorized)
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products"
    Then the response status code should be 401

  Scenario: Get products based on segment (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products?field=id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].id" should exist
    And the JSON node "collection[0].sku" should exist
    And the JSON node "collection[1].id" should exist
    And the JSON node "collection[1].sku" should exist

  Scenario: Get products based on segment from select (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@segment_2@/products?field=id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should be equal to the number 1
    And the JSON node "collection[0].id" should exist
    And the JSON node "collection[0].sku" should exist

  Scenario: Get products based on segment (order by sku)
    Given I am Authenticated as "test@ergonode.com"
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products?field=sku"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].id" should exist
    And the JSON node "collection[0].sku" should exist
    And the JSON node "collection[1].id" should exist
    And the JSON node "collection[1].sku" should exist

  Scenario: Get products based on segment (filter by sku)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products?limit=25&offset=0&filter=sku=SKU_"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products based on segment (not authorized)
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products"
    Then the response status code should be 401

  Scenario: Get products based on segment (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products based on segment (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en_GB/segments/@segment_3@/products?limit=50&offset=0&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete segment (not authorized)
    When I send a DELETE request to "/api/v1/en_GB/segments/@segment@"
    Then the response status code should be 401

  Scenario: Delete segment (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/segments/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/en_GB/segments/@segment@"
    Then the response status code should be 204
