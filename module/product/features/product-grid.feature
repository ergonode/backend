Feature: Product edit feature

  Scenario: Create text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "type": "TEXT",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_text_attribute"

  Scenario: Create select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
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
    Then the response status code should be 201
    And store response param "id" as "product_edit_select_attribute"

  Scenario: Create multi select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
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
    Then the response status code should be 201
    And store response param "id" as "product_edit_multi_select_attribute"

  Scenario: Create text attribute with long code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "LONG_CODE_ATTRIBUTE_1234567890_1234567890_1234567890_1234567890_1234567890_1234567890_@@random_code@@",
        "type": "TEXT",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_text_attribute_long_code"

  Scenario: Create date attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": [],
        "parameters": {"format":"yyyy-MM-dd"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_date_attribute_code"

  Scenario: Create numeric attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "NUMERIC_@@random_code@@",
        "type": "NUMERIC",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_numeric_attribute"

  Scenario: Create price attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
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
    Then the response status code should be 201
    And store response param "id" as "product_edit_price_attribute"

  Scenario: Get text attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/attributes/@product_edit_text_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_text_attribute_code"

  Scenario: Get text attribute with long code code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/attributes/@product_edit_text_attribute_long_code@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_text_attribute_long_code_code"

  Scenario: Get numeric attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/attributes/@product_edit_numeric_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_numeric_attribute_code"

  Scenario: Get price attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/attributes/@product_edit_price_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_price_attribute_code"

  Scenario: Get select attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/attributes/@product_edit_select_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_select_attribute_code"

  Scenario: Get multi select attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/attributes/@product_edit_multi_select_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_multi_select_attribute_code"


  Scenario: Create template
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_template"

  Scenario: Create product
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Create product
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "edit_product"

  Scenario: Add to product select value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@product@/draft/@product_edit_select_attribute@/value" with body:
      """
      {
       "value":"key_12"
      }
      """
    Then the response status code should be 200

  Scenario: Add to product multi select value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@product@/draft/@product_edit_multi_select_attribute@/value" with body:
      """
      {
       "value":["key_12"]
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_text_attribute@/value" with body:
      """
      {
        "value": "text attribute value"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text long code value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_text_attribute_long_code@/value" with body:
      """
      {
        "value": "text with long code attribute value"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product select value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_select_attribute@/value" with body:
      """
      {
       "value":"key_1"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product multi select value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_multi_select_attribute@/value" with body:
      """
      {
       "value":["key_1"]
      }
      """
    Then the response status code should be 200

  Scenario: Edit product numeric value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_numeric_attribute@/value" with body:
      """
      {
        "value": 10.99
      }
      """
    Then the response status code should be 200

  Scenario: Edit product price value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_price_attribute@/value" with body:
      """
      {
        "value": 12.66
      }
      """
    Then the response status code should be 200

  Scenario: Apply edit product draft
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/persist"
    Then the response status code should be 204

  Scenario: Apply product draft
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@product@/draft/persist"
    Then the response status code should be 204

  Scenario: Request product grid filtered by select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_select_attribute_code@&filter=@product_edit_select_attribute_code@=key_1"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by multi select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_multi_select_attribute_code@&filter=@product_edit_multi_select_attribute_code@=key_1"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by text attribute with extended flag
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?extended&columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true
    #And the response body matches:
    #"""
    #  /"value": "text attribute value"/
    #"""

  Scenario: Request product grid filtered by text attribute with long code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_text_attribute_long_code_code@&filter=@product_edit_text_attribute_long_code_code@=text"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by numeric attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_numeric_attribute_code@&filter=@product_edit_numeric_attribute_code@=10.99"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by numeric attribute with extended flag
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?extended&columns=@product_edit_numeric_attribute_code@&filter=@product_edit_numeric_attribute_code@=10.99"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true
    #And the response body matches:
    #"""
    #  /"value": 10.99/
    #"""

  Scenario: Request product grid filtered by price attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_price_attribute_code@&filter=@product_edit_price_attribute_code@=12.66"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by price attribute with extended flag
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?extended&columns=@product_edit_price_attribute_code@&filter=@product_edit_price_attribute_code@=12.66"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true
    #And the response body matches:
    #"""
    #  /"value": 12.66/
    #"""
    #And the response body matches:
    #"""
    #  /"suffix": "PLN"/
    #"""

  Scenario: Request product grid filtered by text attribute null
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@="
    Then the response status code should be 200

  Scenario: Request product date range
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_date_attribute_code@&filter=esa_created_at:EN%3E%3D2020-01-06%3Besa_created_at:EN%3C%3D2020-01-08"
    Then the response status code should be 200

  Scenario: Request product numeric range
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_numeric_attribute@&filter=@product_edit_numeric_attribute@%3E%3D1%3B@product_edit_numeric_attribute@%3C%3D3"
    Then the response status code should be 200

  Scenario: Request product order by index
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_text_attribute_code@&,index&field=index&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products?columns=@product_edit_text_attribute_code@&,index&field=@product_edit_text_attribute_code@&order=DESC"
    Then the response status code should be 200
