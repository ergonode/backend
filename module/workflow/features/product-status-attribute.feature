Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get new status ID
    When I send a GET request to "/api/v1/en_GB/status?limit=1&offset=0&filter=code%3Dnew"
    And store response param "collection[0].id" as "new_status_id"

  Scenario: Create test status
    When I send a POST request to "/api/v1/en_GB/status" with body:
      """
      {
        "color": "#ff0000",
        "code": "CODE @@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "test_status_id"

  Scenario: Get esa_status id
    When I send a GET request to "/api/v1/en_GB/attributes/system?limit=50&offset=0&filter=code%3Desa_status"
    Then the response status code should be 200
    And store response param "collection[0].id" as "esa_status_id"

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

  Scenario: Delete status has transition
    When I send a DELETE request to "/api/v1/en/status/@new_status_id@"
    Then the response status code should be 409

  Scenario: Edit product status
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_id@",
            "payload": [
              {
                "id": "@esa_status_id@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "@test_status_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Delete status has transition
    When I send a DELETE request to "/api/v1/en/status/@test_status_id@"
    Then the response status code should be 409

  Scenario: Edit product status
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_id@",
            "payload": [
              {
                "id": "@esa_status_id@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "@new_status_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Delete status has transition
    When I send a DELETE request to "/api/v1/en/status/@test_status_id@"
    Then the response status code should be 204