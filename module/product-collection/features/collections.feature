Feature: Product collection module

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
    Given remember param "product_1_sku" with value "SKU_@@random_code@@"
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "@product_1_sku@",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_1"

  Scenario: Create product
    Given remember param "product_2_sku" with value "SKU_@@random_code@@"
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "@product_2_sku@",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_2"

  Scenario: Create product
    Given remember param "segment_product_2_sku" with value "SEGMENT_SKU_@@random_code@@"
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "@segment_product_2_sku@",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_product_1"

  Scenario: Create product
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SEGMENT_SKU_@@random_code@@",
        "templateId": "@product_template@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_product_2"

  Scenario: Create condition set
    Given current authentication token
    Given the request body is:
      """
     {"conditions":[{"type":"PRODUCT_SKU_EXISTS_CONDITION","operator":"WILDCARD","value":"SEGMENT__SKU"}]}
      """
    Given I request "/api/v1/EN/conditionsets" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment_conditionset"

  Scenario: Create segment
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SEG_1_@@random_code@@",
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


  Scenario: Create product collection type (not authorized)
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then unauthorized response is received

  Scenario: Create first product collection type
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "DE": "Name DE",
                 "EN": "Name EN"
                 }
      }
      """
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_collection_type_1"

  Scenario: Create second product collection type
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "DE": "Name DE",
                 "EN": "Name EN"
                 }
      }
      """
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_collection_type_2"

  Scenario: Create second product collection type
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "DE": "Name DE",
                 "EN": "Name EN"
                 }
      }
      """
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_collection_type_3"

  Scenario: Create product collection type (only code)
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@"
      }
      """
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then created response is received

  Scenario: Create product collection type (wrong not correct code)
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT/. .,.]_@@random_code@@"
      }
      """
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then validation error response is received

  Scenario: Create product collection type (wrong not correct name)
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "DE": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "EN": "Name EN"
                 }
      }
      """
    When I request "/api/v1/EN/collections/type" using HTTP POST
    Then validation error response is received

  Scenario: Update product collection type (not authorized)
    When I request "/api/v1/EN/collections/type/@product_collection_type_1@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update product collection type (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/type/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update product collection type (no content)
    Given current authentication token
    Given the request body is:
      """
      {
      }
      """
    When I request "/api/v1/EN/collections/type/@product_collection_type_1@" using HTTP PUT
    Then validation error response is received

  Scenario: Update product collection type
    Given current authentication token
    Given the request body is:
      """
      {
          "name": {
                 "DE": "Name DE",
                 "EN": "New Name EN"
                 }
      }
      """
    When I request "/api/v1/EN/collections/type/@product_collection_type_1@" using HTTP PUT
    Then the response code is 204

  Scenario: Request product collection type (not authorized)
    When I request "/api/v1/EN/collections/type/@product_collection_type_1@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Request product collection type (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/type/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Request product collection type
    Given current authentication token
    When I request "/api/v1/EN/collections/type/@product_collection_type_1@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
       / "EN": "New Name EN"/
    """

  Scenario: Get product collection type (not authorized)
    When I request "/api/v1/EN/collections/type" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product collection type (order by code)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?field=code" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection type (order by name)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?field=name" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection type (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?limit=25&offset=0&filter=code=text_" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get product collection type (filter by null code)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?limit=25&offset=0&filter=code=" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """


  Scenario: Get product collection type (not authorized)
    When I request "/api/v1/EN/collections/type" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product collection type (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?limit=25&offset=0&filter=name=Name" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get product collection type (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?limit=50&offset=0&order=ASC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products collection type  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections/type?limit=50&offset=0&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Delete product collection type (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/type/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete product collection type (not authorized)
    When I request "/api/v1/EN/collections/type/@product_collection_type_2@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete product collection type
    Given current authentication token
    When I request "/api/v1/EN/collections/type/@product_collection_type_2@" using HTTP DELETE
    Then empty response is received


  Scenario: Request product collection type after deletion
    Given current authentication token
    When I request "/api/v1/EN/collections/type/@product_collection_type_2@" using HTTP GET
    Then not found response is received

  Scenario: Create product collection (not authorized)
    When I request "/api/v1/EN/collections" using HTTP POST
    Then unauthorized response is received

  Scenario: Create first product collection
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "DE": "Name DE",
             "EN": "Name EN"
          },
          "description": {
            "DE": "Description DE",
            "EN": "Description EN"
          },
          "typeId": "@product_collection_type_1@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_collection_1"

  Scenario: Create second product collection
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "DE": "Name DE",
             "EN": "Name EN"
          },
          "description": {
            "DE": "Description DE",
            "EN": "Description EN"
          },
          "typeId": "@product_collection_type_1@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_collection_2"

  Scenario: Create product collection (no name and desc)
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "typeId": "@product_collection_type_1@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then created response is received

  Scenario: Create product collection (wrong code)
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_//?$@@random_code@@",
          "typeId": "@product_collection_type_1@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then validation error response is received

  Scenario: Create product collection (wrong not correct name)
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@",
              "name": {
                 "DE": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "EN": "Name EN"
                 },
              "typeId": "@product_collection_type_1@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then validation error response is received

  Scenario: Create product collection (wrong not correct description)
    Given current authentication token
    Given the request body is:
      """
      {
           "code": "TEXT_@@random_code@@",
              "description": {
                 "DE": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
                 "EN": "Description EN"
                 },
              "typeId": "@product_collection_type_1@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then validation error response is received

  Scenario: Create product collection (not existing uuid)
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "DE": "Name DE",
             "EN": "Name EN"
          },
          "description": {
            "DE": "Description DE",
            "EN": "Description EN"
          },
          "typeId": "@@static_uuid@@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then validation error response is received

  Scenario: Create product collection (not uuid)
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "name": {
             "DE": "Name DE",
             "EN": "Name EN"
          },
          "description": {
            "DE": "Description DE",
            "EN": "Description EN"
          },
          "typeId": "@@random_code@@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then validation error response is received

  Scenario: Update product collection (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update product collection (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update product collection (no content)
    Given current authentication token
    Given the request body is:
      """
      {
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@" using HTTP PUT
    Then validation error response is received

  Scenario: Update product collection
    Given current authentication token
    Given the request body is:
      """
      {
          "name": {
                 "DE": "Name DE",
                 "EN": "New Name EN"
                 },
          "description": {
                 "DE": "Description DE",
                 "EN": "New Description EN"
                 },
          "typeId": "@product_collection_type_3@"
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@" using HTTP PUT
    Then the response code is 204

  Scenario: Request product collection (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Request product collection (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Request product collection
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
       / "EN": "New Name EN"/
    """
    And the response body matches:
    """
       / "EN": "New Description EN"/
    """

  Scenario: Get product collection (not authorized)
    When I request "/api/v1/EN/collections" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product collection (order by code)
    Given current authentication token
    When I request "/api/v1/EN/collections?field=code" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection (order by name)
    Given current authentication token
    When I request "/api/v1/EN/collections?field=name" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection (order by description)
    Given current authentication token
    When I request "/api/v1/EN/collections?field=description" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection (order by typeid)
    Given current authentication token
    When I request "/api/v1/EN/collections?field=type_id" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/collections?limit=25&offset=0&filter=code=text_" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get product collection (filter by null code)
    Given current authentication token
    When I request "/api/v1/EN/collections?limit=25&offset=0&filter=code=" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get product collection (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/collections?limit=25&offset=0&filter=name=Name" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """


  Scenario: Get product collection (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/collections?limit=25&offset=0&filter=description=Description" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get product collection (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/collections?order=ASC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products collection  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections?order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get product collection (not authorized)
    When I request "/api/v1/EN/collections" using HTTP GET
    Then unauthorized response is received

  Scenario: Delete product collection (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete product collection (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_2@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete product collection
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_2@" using HTTP DELETE
    Then empty response is received

  Scenario: Request product collection after deletion
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_2@" using HTTP GET
    Then not found response is received

  Scenario: Create product collection element (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@/elements" using HTTP POST
    Then unauthorized response is received

  Scenario: Add product collection element
    Given current authentication token
    Given the request body is:
      """
      {
          "productId": "@product_1@",
          "visible": true
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements" using HTTP POST
    Then created response is received

  Scenario: Add product collection element (wrong product not uuid)
    Given current authentication token
    Given the request body is:
      """
      {
          "productId": "@@random_code@@",
          "visible": true
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements" using HTTP POST
    Then validation error response is received

  Scenario: Add product collection element (wrong product doesn't exist)
    Given current authentication token
    Given the request body is:
      """
      {
          "productId": "@@static_uuid@@",
          "visible": true
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements" using HTTP POST
    Then validation error response is received

  Scenario: Update product collection element (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update product collection element (not found product)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update product collection element (not found collection)
    Given current authentication token
    When I request "/api/v1/EN/collections/@@static_uuid@@/elements/@product_1@" using HTTP PUT
    Then not found response is received

  Scenario: Update product collection element (no content)
    Given current authentication token
    Given the request body is:
      """
      {
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP PUT
    Then validation error response is received

  Scenario: Update product collection element
    Given current authentication token
    Given the request body is:
      """
      {
        "visible": false
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP PUT
    Then the response code is 204

  Scenario: Request product collection element (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Request product collection element (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Request product collection element
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
       / "visible": false/
    """

  Scenario: Get product collection element (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@/elements" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product collection element (order by visible)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements?field=visible" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"visible"/
    """

  Scenario: Get product collection element (order by product_id)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements?field=product_id" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"product_id"/
    """

  Scenario: Get product collection element (order by product_collection_id)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements?field=product_collection_id" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"product_id"/
    """

  Scenario: Get product collection element (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements?limit=25&offset=0&filter=visible=true" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get product collection element (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements?limit=50&offset=0&order=ASC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products collection element  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements?limit=50&offset=0&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Delete product collection element (not found)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete product collection element (not authorized)
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete product collection element
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP DELETE
    Then empty response is received


  Scenario: Add multiple product collection element
    Given current authentication token
    Given the request body is:
      """
      {
            "segments": [
              "@segment@"
            ],
            "skus": "@product_1_sku@ , @product_2_sku@"
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/multiple" using HTTP POST
    Then created response is received


  Scenario: Get products collection element  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@segment_product_1@" using HTTP GET
    Then the response code is 200

  Scenario: Get products collection element  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@segment_product_2@" using HTTP GET
    Then the response code is 200

  Scenario: Get products collection element  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_1@" using HTTP GET
    Then the response code is 200

  Scenario: Get products collection element  (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/@product_2@" using HTTP GET
    Then the response code is 200

  Scenario: Add multiple product collection element (wrong segment)
    Given current authentication token
    Given the request body is:
      """
      {
            "segments": [
              "@@random_uuid@@"
            ]
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/multiple" using HTTP POST
    Then validation error response is received

  Scenario: Add multiple product collection element (wrong segment not uuid)
    Given current authentication token
    Given the request body is:
      """
      {
            "segments": [
              "@@random_code@@"
            ]
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/multiple" using HTTP POST
    Then validation error response is received

  Scenario: Add multiple product collection element (wrong skus)
    Given current authentication token
    Given the request body is:
      """
      {
            "skus": "@@random_code@@ , @@random_code@@"
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/multiple" using HTTP POST
    Then validation error response is received


  Scenario: Add multiple product collection element (wrong parameter name)
    Given current authentication token
    Given the request body is:
      """
      {
            "sfesfeskus": "@product_1_sku@"
      }
      """
    When I request "/api/v1/EN/collections/@product_collection_1@/elements/multiple" using HTTP POST
    Then validation error response is received
