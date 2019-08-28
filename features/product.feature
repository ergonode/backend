Feature: Product module

#  Scenario: Create product
#    Given Current authentication token
#    Given the request body is:
#      """
#      {
#        "sku": "SKU_@@random_code@@",
#        "templateId": "string",
#        "categoryIds": []
#      }
#      """
#    When I request "/api/v1/EN/products" using HTTP POST
#    Then created response is received
#    And remember response param "id" as "product"

  Scenario: Create product (not authorized)
    When I request "/api/v1/EN/products" using HTTP POST
    Then unauthorized response is received

#  Scenario: Update product
#    Given Current authentication token
#    Given the request body is:
#      """
#      {
#        "templateId": "string",
#        "categoryIds": []
#      }
#      """
#    When I request "/api/v1/EN/products/@product@" using HTTP PUT
#    Then the response code is 200

  Scenario: Update product (not authorized)
    When I request "/api/v1/EN/products/@product@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update product (not found)
    Given Current authentication token
    When I request "/api/v1/EN/products/@@static_uuid@@" using HTTP PUT
    Then not found response is received

#  Scenario: Get product
#    Given Current authentication token
#    When I request "/api/v1/EN/products/@product@" using HTTP GET
#    Then the response code is 200

  Scenario: Get product (not authorized)
    When I request "/api/v1/EN/products/@product@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get product (not found)
    Given Current authentication token
    When I request "/api/v1/EN/products/@@static_uuid@@" using HTTP GET
    Then not found response is received

  # TODO Check product grid
  # TODO Check create product action with all incorrect possibilities
  # TODO Check update product action with all incorrect possibilities
