Feature: Product edit feature

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

