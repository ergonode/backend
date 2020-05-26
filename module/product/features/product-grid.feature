Feature: Product edit feature

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
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_text_attribute"

  Scenario: Create select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_select_attribute"

  Scenario: Create option 1 for select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en/attributes/@product_edit_select_attribute@/options" with body:
      """
      {
        "code": "key_1",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_option_1"

  Scenario: Create option 2 for select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en/attributes/@product_edit_select_attribute@/options" with body:
      """
      {
        "code": "key_12",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_option_2"

  Scenario: Create multi select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "MULTI_SELECT_@@random_code@@",
        "type": "MULTI_SELECT",
        "scope": "local",
        "multilingual": true,
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_multi_select_attribute"

  Scenario: Create option 1 for multiselect attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en/attributes/@product_edit_multi_select_attribute@/options" with body:
      """
      {
        "code": "key_1",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_1"

  Scenario: Create option 2 for multiselect attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/v1/en/attributes/@product_edit_multi_select_attribute@/options" with body:
      """
      {
        "code": "key_12",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_2"

  Scenario: Create text attribute with long code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "LONG_COde_ATTRIBUTE_1234567890_1234567890_1234567890_1234567890_1234567890_1234567890_@@random_code@@",
        "type": "TEXT",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_text_attribute_long_code"

  Scenario: Create date attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": [],
        "scope": "local",
        "parameters": {"format":"yyyy-MM-dd"}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_date_attribute_code"

  Scenario: Create numeric attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "NUMERIC_@@random_code@@",
        "type": "NUMERIC",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_numeric_attribute"

  Scenario: Create price attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "scope": "local",
        "parameters": {
          "currency": "PLN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_price_attribute"

  Scenario: Create Image attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "IMAGE_@@random_code@@",
        "type": "IMAGE",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_image_attribute"

  Scenario: Get text attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_text_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_text_attribute_code"

  Scenario: Get text attribute with long code code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_text_attribute_long_code@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_text_attribute_long_code_code"

  Scenario: Get numeric attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_numeric_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_numeric_attribute_code"

  Scenario: Get price attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_price_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_price_attribute_code"

  Scenario: Get select attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_select_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_select_attribute_code"

  Scenario: Get multi select attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_multi_select_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_multi_select_attribute_code"

  Scenario: Get image attribute code
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/en/attributes/@product_edit_image_attribute@"
    Then the response status code should be 200
    And store response param "code" as "product_edit_image_attribute_code"


  Scenario: Create template
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/templates" with body:
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
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
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
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
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
    When I send a PUT request to "api/v1/en/products/@product@/draft/@product_edit_select_attribute@/value" with body:
      """
      {
       "value":"@select_option_2@"
      }
      """
    Then the response status code should be 200

  Scenario: Add to product multi select value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en/products/@product@/draft/@product_edit_multi_select_attribute@/value" with body:
      """
      {
       "value": ["@multi_select_option_2@"]
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/@product_edit_text_attribute@/value" with body:
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
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/@product_edit_text_attribute_long_code@/value" with body:
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
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/@product_edit_select_attribute@/value" with body:
      """
      {
       "value":"@select_option_1@"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product multi select value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/@product_edit_multi_select_attribute@/value" with body:
      """
      {
       "value":["@multi_select_option_1@"]
      }
      """
    Then the response status code should be 200

  Scenario: Edit product numeric value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/@product_edit_numeric_attribute@/value" with body:
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
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/@product_edit_price_attribute@/value" with body:
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
    When I send a PUT request to "api/v1/en/products/@edit_product@/draft/persist"
    Then the response status code should be 204

  Scenario: Apply product draft
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/en/products/@product@/draft/persist"
    Then the response status code should be 204

  Scenario: Request product grid filtered by select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_select_attribute_code@&filter=@product_edit_select_attribute_code@=@select_option_1@"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by multi select attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_multi_select_attribute_code@&filter=@product_edit_multi_select_attribute_code@=@multi_select_option_1@"
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by text attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by text attribute with extended flag
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?extended&columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text"
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
    When I send a GET request to "api/v1/en/products?columns=@product_edit_text_attribute_long_code_code@&filter=@product_edit_text_attribute_long_code_code@=text"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by numeric attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_numeric_attribute_code@:en&filter=@product_edit_numeric_attribute_code@:en=10.99"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by numeric attribute with extended flag
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?extended&columns=@product_edit_numeric_attribute_code@&filter=@product_edit_numeric_attribute_code@=10.99"
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
    When I send a GET request to "api/v1/en/products?columns=@product_edit_price_attribute_code@&filter=@product_edit_price_attribute_code@=12.66"
    Then the response status code should be 200
    And the JSON node "info.filtered" should match "/1/"
    And the JSON node "columns[0].visible" should be true
    And the JSON node "columns[0].editable" should be true
    And the JSON node "columns[0].deletable" should be true

  Scenario: Request product grid filtered by price attribute with extended flag
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?extended&columns=@product_edit_price_attribute_code@&filter=@product_edit_price_attribute_code@=12.66"
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
    When I send a GET request to "api/v1/en/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@="
    Then the response status code should be 200

  Scenario: Request product date range
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_date_attribute_code@&filter=esa_created_aten%3E%3D2020-01-06%3Besa_created_aten%3C%3D2020-01-08"
    Then the response status code should be 200

  Scenario: Request product numeric range
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_numeric_attribute@&filter=@product_edit_numeric_attribute@%3E%3D1%3B@product_edit_numeric_attribute@%3C%3D3"
    Then the response status code should be 200

  Scenario: Request product order by index
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_text_attribute_code@&,index&field=index&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_text_attribute_code@&,index&field=@product_edit_text_attribute_code@&order=DESC"
    Then the response status code should be 200

  Scenario: Request product order by not exists attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/en/products?columns=@product_edit_text_attribute_code@&,index&field=xxxxxxx@&order=DESC"
    Then the response status code should be 200
