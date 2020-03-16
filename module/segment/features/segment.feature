Feature: Segment module

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
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
    When I send a POST request to "/api/v1/EN/templates" with body:
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
    When I send a POST request to "/api/v1/EN/categories" with body:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"DE": "Test DE", "EN": "Test EN"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_category"

  Scenario: Create product
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Create condition set
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send a POST request to "/api/v1/EN/conditionsets" with body:
      """
      {
        "conditions": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_conditionset"

  Scenario: Create segment (not authorized)
    When I send a POST request to "/api/v1/EN/segments"
    Then the response status code should be 401

  Scenario: Create segment
    Given remember param "segment_code" with value "SEG_1_@@random_code@@"
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "PL": "Segment",
          "EN": "Segment"
        },
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment"

  Scenario: Create segment (not unique code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "code": "@segment_code@",
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create segment (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    Then the response status code should be 201

  Scenario: Create segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "code": "SEG_3_@@random_code@@",
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "PL": "Segment",
          "EN": "Segment"
        },
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "segment_3"

  Scenario: Create segment (without description and name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
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
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "PL": "Segment",
          "EN": "Segment"
        },
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Create segment (without condition set)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/segments" with body:
      """
      {
        "code": "SEG_2_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Update segment (not authorized)
    When I send a PUT request to "/api/v1/EN/segments/@segment@"
    Then the response status code should be 401

  Scenario: Update segment (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/segments/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/segments/@segment@" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "name": {
          "PL": "Segment (changed)",
          "EN": "Segment (changed)"
        },
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update segment (without name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/segments/@segment@" with body:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Update segment (without name and description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/EN/segments/@segment@" with body:
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
    When I send a PUT request to "/api/v1/EN/segments/@segment@" with body:
      """
      {
        "name": {
          "PL": "Segment (changed)",
          "EN": "Segment (changed)"
        },
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Get segment (not authorized)
    When I send a GET request to "/api/v1/EN/segments/@segment@"
    Then the response status code should be 401

  Scenario: Get segment (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments/@segment@"
    Then the response status code should be 200

  Scenario: Get segments
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get segments (not authorized)
    When I send a GET request to "/api/v1/EN/segments"
    Then the response status code should be 401

  Scenario: Get segments (order by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get segments (order by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get segments (order by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments?field=description"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get segments (filter by code)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments?limit=25&offset=0&filter=code%3Dsuper"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get segments (filter by name)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments?limit=25&offset=0&filter=name%3Dsuper"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get segments (filter by description)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments?limit=25&offset=0&filter=description%3Dsuper"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"

  Scenario: Get products based on segment (not authorized)
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products"
    Then the response status code should be 401

  Scenario: Get products based on segment (order by id)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products?field=id"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].id" should exist
    And the JSON node "collection[0].sku" should exist

  Scenario: Get products based on segment (order by sku)
    Given I am Authenticated as "test@ergonode.com"
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products?field=sku"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].id" should exist
    And the JSON node "collection[0].sku" should exist

  Scenario: Get products based on segment (filter by sku)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products?limit=25&offset=0&filter=sku=SKU_"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products based on segment (not authorized)
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products"
    Then the response status code should be 401

  Scenario: Get products based on segment (order ASC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products based on segment (order DESC)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/segments/@segment_3@/products?limit=50&offset=0&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete segment (not authorized)
    When I send a DELETE request to "/api/v1/EN/segments/@segment@"
    Then the response status code should be 401

  Scenario: Delete segment (not found)
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/segments/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete segment
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a DELETE request to "/api/v1/EN/segments/@segment@"
    Then the response status code should be 204
