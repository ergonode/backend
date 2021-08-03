Feature: Workflow product grid status column

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_id"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Request product grid with workflow status column
    When I send a GET request to "api/v1/en_GB/products?columns=esa_status"
    Then the response status code should be 200
