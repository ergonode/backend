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

  Scenario: Get text attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_text_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_text_attribute_code"

  Scenario: Get numeric attribute code
    Given current authentication token
    When I request "/api/v1/EN/attributes/@product_edit_numeric_attribute@" using HTTP GET
    Then the response code is 200
    And remember response param "code" as "product_edit_numeric_attribute_code"

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

  Scenario: Edit product numeric value
    Given current authentication token
    Given the request body is:
      """
      {
        "value": "10.99"
      }
      """
    When I request "api/v1/EN/products/@edit_product@/draft/@product_edit_numeric_attribute@/value" using HTTP PUT
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

  Scenario: Request product grid filtered by text attribute null
    Given current authentication token
    When I request "api/v1/EN/products?columns=@product_edit_text_attribute_code@&filter=@product_edit_text_attribute_code@=" using HTTP GET
    Then the response code is 200
