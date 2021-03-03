Feature: batch action product deletion

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id"

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

  Scenario: Add children product
    When I send a POST request to "/api/v1/en_GB/products/@grouping_product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Get products
    When I send a GET request to "/api/v1/en_GB/products"
    Then the response status code should be 200
    And the JSON node "collection[0]" should exist

  Scenario: Create batch action for all products
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE"
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Get notifications
    When I send a GET request to "/api/v1/profile/notifications?field=created_at&ordered=ASC"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].message   | Batch action "delete products" ended  |

  Scenario: Get products
    When I send a GET request to "/api/v1/en_GB/products"
    Then the response status code should be 200
    And the JSON node "collection[0]" should not exist
