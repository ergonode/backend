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

  Scenario: Create textarea attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "TEXT_AREA_@@random_code@@",
        "type": "TEXT_AREA",
        "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_textarea_attribute"

  Scenario: Create select attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "groups": [],
        "options": [
          {"key": "key_a", "value": null},
          {"key": "key_b", "value": null},
          {"key": "key_c", "value": null},
          {"key": "key_d", "value": null}
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
          {"key": "key_aa", "value": null},
          {"key": "key_bb", "value": null},
          {"key": "key_cc", "value": null},
          {"key": "key_dd", "value": null}
        ]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_multi_select_attribute"

  Scenario: Create unit attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "UNIT_@@random_code@@",
        "type": "UNIT",
        "groups": [],
        "parameters": {
          "unit":"KG"
        }
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_unit_attribute"

  Scenario: Create price attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "parameters": {
          "currency":"EUR"
        }
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_price_attribute"

  Scenario: Create date attribute
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "groups": [],
        "parameters": {
          "format":"yyyy-MM-dd"
        }
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_date_attribute"

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
    And remember response param "id" as "edit_product"

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

  Scenario: Edit product textarea value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "textarea attribute value"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_textarea_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product select value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "key_a"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_select_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product multi select value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": ["key_aa", "key_dd"]
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_multi_select_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product unit value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "102030"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_unit_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product price value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "9999.99"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_price_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Edit product date value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "2019-12-30"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_date_attribute@/value" using HTTP PUT
    Then the response code is 200

  Scenario: Apply product draft
    Given current authentication token
    When I request "api/v1/EN/products/@edit_product@/draft/persist" using HTTP PUT
    Then the response code is 204

  Scenario: Request product
    Given current authentication token
    When I request "api/v1/EN/products/@edit_product@" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"value": "text attribute value"/
    """
    And the response body matches:
    """
      /"value": "textarea attribute value"/
    """
    And the response body matches:
    """
      /"value": "key_a"/
    """
    And the response body matches:
    """
      /"value": \[\n[ ]*"key_aa",\n[ ]*"key_dd"\n[ ]*\]/
    """
    And the response body matches:
    """
      /"categories": \[\]/
    """
    And the response body matches:
    """
      /"value": "9999.99"/
    """
    And the response body matches:
    """
      /"value": "102030"/
    """
    And the response body matches:
    """
      /"value": "2019-12-30"/
    """

