Feature: batch action product deletion

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_template"

  Scenario Outline: Create product <number>
    And remember param "product_sku_<number>" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku_<number>@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_<number>"
    Examples:
    |number|
    |1|
    |2|
    |3|

  Scenario: Create batch action with product 1
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@"
            ],
            "included": true
          }
        }
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Create batch action with product 2 and product 3
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "filter": {
          "ids": {
            "list": [
              "@product_id_2@"
            ],
            "included": true
          },
            "query": "esa_sku=@product_sku_3@"
        }
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Get not exists batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@"
    Then the response status code should be 200

  Scenario Outline: Delete product <number> already deleted
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id_<number>@"
    Then the response status code should be 404
    Examples:
    |number|
    |1|
    |2|
    |3|

  Scenario: Create batch action with not exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "filter": {
          "ids": {
            "list": [
              "@@random_uuid@@"
            ],
            "included": true
          }
        }
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Get not exists batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@"
    Then the response status code should be 200
