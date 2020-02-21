Feature: Product module

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

  Scenario: Multimedia upload image
    Given current authentication token
    Given I attach "module/product/features/image/test.jpg" to the request as "upload"
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then created response is received
    And remember response param "id" as "multimedia_id"

  Scenario: Create template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
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

  Scenario: Create product collection type
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
    And remember response param "id" as "product_collection_type"


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
          "typeId": "@product_collection_type@"
      }
      """
    When I request "/api/v1/EN/collections" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_collection"

  Scenario: Add product collection element
    Given current authentication token
    Given the request body is:
      """
      {
          "productId": "@product@",
          "visible": true
      }
      """
    When I request "/api/v1/EN/collections/@product_collection@/elements" using HTTP POST
    Then created response is received

  Scenario: Create product (not authorized)
    When I request "/api/v1/EN/products" using HTTP POST
    Then unauthorized response is received

  Scenario: Create product (wrong product_template no UUID)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "test",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then validation error response is received

  Scenario: Create product (wrong product_template wrong UUID)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@@random_uuid@@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then validation error response is received

  Scenario: Create product (no templateId)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then validation error response is received

  Scenario: Create product (empty categoryIds)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template@",
        "categoryIds": []
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received

  Scenario: Create product (no categoryIds)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template@"
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received

  Scenario: Create product (categoryIds not UUID)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template@",
        "categoryIds": ["test"]
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then validation error response is received

  Scenario: Create product (no categoryIds)
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_template@"
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received

  Scenario: Update product
    Given current authentication token
    Given the request body is:
      """
      {
        "categoryIds": ["@product_category@"]
      }
      """
    When I request "/api/v1/EN/products/@product@" using HTTP PUT
    Then empty response is received

  Scenario: Update product (not authorized)
    When I request "/api/v1/EN/products/@product@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update product (not found)
    Given current authentication token
    When I request "/api/v1/EN/products/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update product (no content)
    Given current authentication token
    Given the request body is:
      """
      {
      }
      """
    When I request "/api/v1/EN/products/@product@" using HTTP PUT
    Then validation error response is received

  Scenario: Update product (categoryID not UUID)
    Given current authentication token
    Given the request body is:
      """
      {
        "categoryIds": ["@@random_md5@@"]
      }
      """
    When I request "/api/v1/EN/products/@product@" using HTTP PUT
    Then validation error response is received

  Scenario: Update product (categoryID wrong UUID)
    Given current authentication token
    Given the request body is:
      """
      {
        "categoryIds": ["@@random_uuid@@"]
      }
      """
    When I request "/api/v1/EN/products/@product@" using HTTP PUT
    Then validation error response is received

  Scenario: Get product
    Given current authentication token
    When I request "/api/v1/EN/products/@product@" using HTTP GET
    Then the response code is 200

  Scenario: Get product (not authorized)
    When I request "/api/v1/EN/products/@product@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product (not found)
    Given current authentication token
    When I request "/api/v1/EN/products/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get product collections  (not authorized)
    When I request "/api/v1/EN/products/@product@/collections" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product collection element (order by code)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?field=code&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection element (order by name)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?field=name&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"name"/
    """

  Scenario: Get product collection element (order by description)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?field=description&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"description"/
    """

  Scenario: Get product collection element (order by type_id)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?field=type_id&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"type_id"/
    """

  Scenario: Get product collection element (order by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?field=elements_count&order=DESC" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"elements_count"/
    """

  Scenario: Get product collection element (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?&filter=code=TEXT" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"code"/
    """

  Scenario: Get product collection element (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?&filter=name=Name" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"name"/
    """

  Scenario: Get product collection element (filter by description)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/collections?&filter=description=Description" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"description"/
    """

  Scenario: Delete product (not found)
    Given current authentication token
    When I request "/api/v1/EN/products/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Delete product (not authorized)
    When I request "/api/v1/EN/products/@product@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete product
    Given current authentication token
    When I request "/api/v1/EN/products/@product@" using HTTP DELETE
    Then empty response is received

  Scenario: Get products (order by id)
    Given current authentication token
    When I request "/api/v1/EN/products?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get products (order by index)
    Given current authentication token
    When I request "/api/v1/EN/products?field=index" using HTTP GET
    Then grid response is received

  Scenario: Get products (order by sku)
    Given current authentication token
    When I request "/api/v1/EN/products?field=sku" using HTTP GET
    Then grid response is received

  Scenario: Get products (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/products?field=index&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get products (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/products?field=index&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get products (filter by index)
    Given current authentication token
    When I request "/api/v1/EN/products?limit=25&offset=0&filter=index%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get products (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/products?limit=25&offset=0&filter=id%3DCAT" using HTTP GET
    Then grid response is received

  Scenario: Get products (filter by sku)
    Given current authentication token
    When I request "/api/v1/EN/products?limit=25&offset=0&filter=sku%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get products (not authorized)
    When I request "/api/v1/EN/products" using HTTP GET
    Then unauthorized response is received
