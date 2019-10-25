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

  Scenario: Get attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_text_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_text_attribute_code"

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

  Scenario: Apply product draft
    Given current authentication token
    When I request "api/v1/EN/products/@edit_product@/draft/persist" using HTTP PUT
    Then the response code is 204

  Scenario: Request product grid filtered by text attribute
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=text" using HTTP GET
    Then the response code is 200
    And the response body matches:
    """
      /"filtered": 1/
    """

  Scenario: Request product grid filtered by text attribute null
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=" using HTTP GET
    Then the response code is 200
