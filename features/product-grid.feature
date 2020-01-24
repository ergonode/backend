Feature: Product edit feature

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TEXT_@@random_code@@",
        "type": "TEXT",
        "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_text_attribute"

  Scenario: Create select attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "groups": [],
         "options": [
        {
            "key": "key_1",
            "value": ""
        },
        {
            "key": "key_12",
            "value": ""
        }
    ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_select_attribute"

  Scenario: Create multi select attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "MULTI_SELECT_@@random_code@@",
        "type": "MULTI_SELECT",
        "groups": [],
         "options": [
        {
            "key": "key_1",
            "value": ""
        },
        {
            "key": "key_12",
            "value": ""
        }
    ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_multi_select_attribute"

  Scenario: Create text attribute with long code
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "LONG_CODE_ATTRIBUTE_1234567890_1234567890_1234567890_1234567890_1234567890_1234567890_@@random_code@@",
        "type": "TEXT",
        "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_text_attribute_long_code"

  Scenario: Create date attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": [],
        "parameters": {"format":"yyyy-MM-dd"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_date_attribute_code"

  Scenario: Create numeric attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "NUMERIC_@@random_code@@",
        "type": "NUMERIC",
        "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_numeric_attribute"

  Scenario: Create price attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "parameters": {
          "currency": "PLN"
        }
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_price_attribute"

  Scenario: Get text attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_text_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_text_attribute_code"

  Scenario: Get text attribute with long code code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_text_attribute_long_code@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_text_attribute_long_code_code"

  Scenario: Get numeric attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_numeric_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_numeric_attribute_code"

  Scenario: Get price attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_price_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_price_attribute_code"

  Scenario: Get select attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_select_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_select_attribute_code"

  Scenario: Get multi select attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_multi_select_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_multi_select_attribute_code"


  Scenario: Create template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    When I request "/api/v1/EN/templates" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_template"

  Scenario: Create product
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "product"

  Scenario: Create product
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "edit_product"

  Scenario: Add to product select value
    Given current authentication token
    Given the request body is:
      """
      {
       "value":"key_12"
      }
      """
    When I request "api/v1/EN/products/@product@/draft/@product_edit_select_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Add to product multi select value
    Given current authentication token
    Given the request body is:
      """
      {
       "value":["key_12"]
      }
      """
    When I request "api/v1/EN/products/@product@/draft/@product_edit_multi_select_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product text value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "text attribute value"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_text_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product text long code value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "text with long code attribute value"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_text_attribute_long_code@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product select value
    Given current authentication token
    Given the request body is:
      """
      {
       "value":"key_1"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_select_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product multi select value
    Given current authentication token
    Given the request body is:
      """
      {
       "value":["key_1"]
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_multi_select_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product numeric value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": 10.99
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_numeric_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product price value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": 12.66
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_price_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Apply edit product draft
    Given current authentication token
    When I request "api/v1/EN/products/@edit_product@/draft/persist" using HTTP PUT
    Then the response code is 204

  Scenario: Apply product draft
    Given current authentication token
    When I request "api/v1/EN/products/@product@/draft/persist" using HTTP PUT
    Then the response code is 204

  Scenario: Request product grid filtered by select attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_select_attribute_code@&filter=@product_edit_select_attribute_code@=key_1" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """

  Scenario: Request product grid filtered by multi select attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_multi_select_attribute_code@&filter=@product_edit_multi_select_attribute_code@=key_1" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """

  Scenario: Request product grid filtered by text attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """

  Scenario: Request product grid filtered by text attribute with extended flag
    Given current authentication token
    When I request "api/v1/EN/products?extended&columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """
    And the response body matches:
    """
      /"value": "text attribute value"/
    """

  Scenario: Request product grid filtered by text attribute with long code
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_long_code_code@&filter=@product_edit_text_attribute_long_code_code@=text" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """

  Scenario: Request product grid filtered by numeric attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_numeric_attribute_code@&filter=@product_edit_numeric_attribute_code@=10.99" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """

  Scenario: Request product grid filtered by numeric attribute with extended flag
    Given current authentication token
    When I request "api/v1/EN/products?extended&columns=@product_edit_numeric_attribute_code@&filter=@product_edit_numeric_attribute_code@=10.99" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """
    And the response body matches:
    """
      /"value": 10.99/
    """

  Scenario: Request product grid filtered by price attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_price_attribute_code@&filter=@product_edit_price_attribute_code@=12.66" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """

  Scenario: Request product grid filtered by price attribute with extended flag
    Given current authentication token
    When I request "api/v1/EN/products?extended&columns=@product_edit_price_attribute_code@&filter=@product_edit_price_attribute_code@=12.66" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """
    And the response body matches:
    """
      /"visible": true/
    """
    And the response body matches:
    """
      /"editable": true/
    """
    And the response body matches:
    """
      /"deletable": true/
    """
    And the response body matches:
    """
      /"value": 12.66/
    """
    And the response body matches:
    """
      /"suffix": "PLN"/
    """

  Scenario: Request product grid filtered by text attribute null
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=" using HTTP GET
    Then the response code is 200

  Scenario: Request product date range
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_date_attribute_code@&filter=esa_created_at:EN%3E%3D2020-01-06%3Besa_created_at:EN%3C%3D2020-01-08"
    Then the response code is 200

  Scenario: Request product numeric range
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_numeric_attribute@&filter=@product_edit_numeric_attribute@%3E%3D1%3B@product_edit_numeric_attribute@%3C%3D3"
    Then the response code is 200

  Scenario: Request product order by index
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&,index&field=index&order=DESC"
    Then the response code is 200

  Scenario: Request product order by attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&,index&field=@product_edit_text_attribute_code@&order=DESC"
    Then the response code is 200
