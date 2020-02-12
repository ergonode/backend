Feature: Segment module

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_template_attribute"

  Scenario: Create template
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_template"

  Scenario: Create category
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "CATEGORY_@@random_uuid@@",
        "name": {"DE": "Test DE", "EN": "Test EN"}
      }
      """
    When I request "/api/v1/EN/categories" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_category"

  Scenario: Create product
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "product"

  Scenario: Create condition set
    Given current authentication token
    Given the request body is:
      """
      {
        "conditions": []
      }
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_conditionset"

  Scenario: Create segment (not authorized)
    When I request "/api/v1/EN/segments" using HTTP POST
    Then unauthorized response is received

  Scenario: Create segment
    Given remember param "segment_code" with value "SEG_1_@@random_code@@"
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment"

  Scenario: Create segment (not unique code)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments" using HTTP POST
    Then validation error response is received

  Scenario: Create segment (without name)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received

  Scenario: Create segment
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_3"

  Scenario: Create segment (without description and name)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_2_@@random_code@@",
        "condition_set_id": "@segment_conditionset@"
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received

  Scenario: Create segment (without code)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments" using HTTP POST
    Then validation error response is received

  Scenario: Create segment (without condition set)
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_2_@@random_code@@"
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received

  Scenario: Update segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update segment
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Update segment (without name)
    Given current authentication token
    Given the request body is:
      """
      {
        "condition_set_id": "@segment_conditionset@",
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Update segment (without name and description)
    Given current authentication token
    Given the request body is:
      """
      {
        "condition_set_id": "@segment_conditionset@"
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Update segment (without condition set)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  Scenario: Get segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get segment
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment@" using HTTP GET
    Then the response code is 200

  Scenario: Get segments
    Given current authentication token
    When I request "/api/v1/EN/segments" using HTTP GET
    Then grid response is received

  Scenario: Get segments (not authorized)
    When I request "/api/v1/EN/segments" using HTTP GET
    Then unauthorized response is received

  Scenario: Get segments (order by code)
    Given current authentication token
    When I request "/api/v1/EN/segments?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get segments (order by name)
    Given current authentication token
    When I request "/api/v1/EN/segments?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get segments (order by description)
    Given current authentication token
    When I request "/api/v1/EN/segments?field=description" using HTTP GET
    Then grid response is received

  Scenario: Get segments (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/segments?limit=25&offset=0&filter=code%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get segments (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/segments?limit=25&offset=0&filter=name%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get segments (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/segments?limit=25&offset=0&filter=description%3Dsuper" using HTTP GET
    Then grid response is received

  Scenario: Get products based on segment (not authorized)
    When I request "/api/v1/EN/segments/@segment_3@/products" using HTTP GET
    Then unauthorized response is received

  Scenario: Get products based on segment (order by id)
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment_3@/products?field=id" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"id"/
    """

  Scenario: Get products based on segment (order by sku)
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment_3@/products?field=sku" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"sku"/
    """

  Scenario: Get products based on segment (filter by sku)
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment_3@/products?limit=25&offset=0&filter=sku=SKU_" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products based on segment (not authorized)
    When I request "/api/v1/EN/segments/@segment_3@/products" using HTTP GET
    Then unauthorized response is received

  Scenario: Get products based on segment (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment_3@/products?limit=50&offset=0&order=ASC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products based on segment (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment_3@/products?limit=50&offset=0&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Delete segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete segment
    Given current authentication token
    When I request "/api/v1/EN/segments/@segment@" using HTTP DELETE
    Then empty response is received
