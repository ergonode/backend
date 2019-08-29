Feature: Product module

  # TODO Do something with this! How to chain them?
  Scenario: Get attribute groups dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then the response code is 200
    And remember first attribute group as "attribute_group"

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_template_attribute"

  Scenario: Create image attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "IMAGE_@@random_code@@",
          "type": "IMAGE",
          "groups": ["@attribute_group@"],
          "parameters": {"formats": ["jpg"]}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_image_attribute"

  Scenario: Create template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@product_image_attribute@",
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

  Scenario: Create product (not authorized)
    When I request "/api/v1/EN/products" using HTTP POST
    Then unauthorized response is received

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

  # TODO Check product grid
  # TODO Check create product action with all incorrect possibilities
  # TODO Check update product action with all incorrect possibilities
