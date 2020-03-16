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

  Scenario: Create textarea attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
      """
      {
        "code": "TEXT_AREA_@@random_code@@",
        "type": "TEXT_AREA",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_textarea_attribute"

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
          {"key": "key_a", "value": null},
          {"key": "key_b", "value": null},
          {"key": "key_c", "value": null},
          {"key": "key_d", "value": null}
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
          {"key": "key_aa", "value": null},
          {"key": "key_bb", "value": null},
          {"key": "key_cc", "value": null},
          {"key": "key_dd", "value": null}
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_multi_select_attribute"

  Scenario: Create unit attribute
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/attributes" with body:
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
    Then the response status code should be 201
    And store response param "id" as "product_edit_unit_attribute"

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
          "currency":"EUR"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_price_attribute"

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
        "parameters": {
          "format":"yyyy-MM-dd"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_date_attribute"

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
    And store response param "id" as "edit_product"

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

  Scenario: Edit product textarea value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_textarea_attribute@/value" with body:
      """
      {
        "value": "textarea attribute value"
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
        "value": "key_a"
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
        "value": ["key_aa", "key_dd"]
      }
      """
    Then the response status code should be 200

  Scenario: Edit product unit value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_unit_attribute@/value" with body:
      """
      {
        "value": "102030"
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
        "value": "9999.99"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product date value
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/@product_edit_date_attribute@/value" with body:
      """
      {
        "value": "2019-12-30"
      }
      """
    Then the response status code should be 200

  Scenario: Apply product draft
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "api/v1/EN/products/@edit_product@/draft/persist"
    Then the response status code should be 204

  Scenario: Request product
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "api/v1/EN/products/@edit_product@"
    Then the response status code should be 200
#    And print last JSON response
#    And the JSON nodes should be equal to:
#    | attributes.text_tutaj_uuid | text attribute value |
#    | attributes.text_area_@product_edit_textarea_attribute@ | textarea attribute value |
#
#    And the response body matches:
#    """
#      /"value": "text attribute value"/
#    """
#    And the response body matches:
#    """
#      /"value": "textarea attribute value"/
#    """
#    And the response body matches:
#    """
#      /"value": "key_a"/
#    """
#    And the response body matches:
#    """
#      /"value": \[\n[ ]*"key_aa",\n[ ]*"key_dd"\n[ ]*\]/
#    """
#    And the response body matches:
#    """
#      /"categories": \[\]/
#    """
#    And the response body matches:
#    """
#      /"value": "9999.99"/
#    """
#    And the response body matches:
#    """
#      /"value": "102030"/
#    """
#    And the response body matches:
#    """
#      /"value": "2019-12-30"/
#    """

