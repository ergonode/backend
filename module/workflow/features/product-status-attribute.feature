Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "template_id"

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
    And store response param "id" as "product_id"

  Scenario Outline: Get status for product in <language> language
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/workflow/<language>"
    Then the response status code should be 200
    And the JSON node "status.code" should exist
    Examples:
      | language |
      | en_GB    |
      | pl_PL    |
      | fr_FR    |
      | de_DE    |

