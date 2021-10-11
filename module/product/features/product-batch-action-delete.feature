Feature: batch action product deletion

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_template_id"

  Scenario: Create grouping product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "GROUPING-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "grouping_product_id"

  Scenario: Create simple product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "simple_product_id"

  Scenario: Create simple product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "simple_product_id_no_relation"

  Scenario: Add children product
    When I send a POST request to "/api/v1/en_GB/products/@grouping_product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Create batch action when no filter
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE"
      }
    """
    Then the response status code should be 400

  Scenario Outline: Create batch action - validation error
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
      """
      {
        "type": "PRODUCT_DELETE",
        "filter": {
          "ids": {
            "list": <ids>,
            "included": true
          }
        }
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.<error_column>" should exist
    Examples:
      | ids          | error_column    |
      | ["not uuid"] | filter.ids.list |
      | []           | list            |


  Scenario: Create batch action for one products auto errors
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "autoEndOnErrors": true,
        "filter": {
          "ids": {
            "list": [
              "@simple_product_id@"
            ],
            "included": true
          }
        }
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_one_no_errors_id"

  Scenario: Get second batch action status
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_one_no_errors_id@"
    And the JSON node "status" should contain "ENDED"

  Scenario: Create batch action for one products no auto errors
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "autoEndOnErrors": false,
        "filter": {
          "ids": {
            "list": [
              "@simple_product_id@"
            ],
            "included": true
          }
        }
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_one_errors_id"

  Scenario: Get second batch action status
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_one_errors_id@"
    Then the response status code should be 200
    And the JSON node "status" should contain "WAITING_FOR_DECISION"

  Scenario: Reprocess batch action for one products no auto errors
    And I send a "PATCH" request to "/api/v1/en_GB/batch-action/@batch_action_one_errors_id@/reprocess" with body:
    """
      {
         "autoEndOnErrors": false
      }
    """
    Then the response status code should be 204

  Scenario: Get second batch action status
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_one_errors_id@"
    Then the response status code should be 200
    And the JSON node "status" should contain "WAITING_FOR_DECISION"

  Scenario: End WAITING_FOR_DECISION batch action
    And I send a "PUT" request to "/api/v1/en_GB/batch-action/@batch_action_one_errors_id@/end"
    Then the response status code should be 204

  Scenario: End ENDED batch action
    And I send a "PUT" request to "/api/v1/en_GB/batch-action/@batch_action_one_no_errors_id@/end"
    Then the response status code should be 400

  Scenario: Get second batch action status after end
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_one_errors_id@"
    Then the response status code should be 200
    And the JSON node "status" should contain "ENDED"

  Scenario: Create batch action for all products
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "filter": "all"
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Create batch action for all products with auto Error
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "autoEndOnErrors": false,
        "filter": "all"
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_2_id"

  Scenario: Get batch action entry grid
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@/entries"
    Then the response status code should be 200

  Scenario: Get simple product
    When I send a GET request to "/api/v1/en_GB/products/@simple_product_id_no_relation@"
    Then the response status code should be 404

  Scenario: Get grouping product
    When I send a GET request to "/api/v1/en_GB/products/@grouping_product_id@"
    Then the response status code should be 404
