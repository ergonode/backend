Feature: batch action product deletion

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_template_id"

  Scenario: Get attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=select_attribute_local;type=SELECT&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id"

  Scenario:  Get option id
    When I send a GET request to "/api/v1/en_GB/attributes/@attribute_id@/options/grid?filter=code=option_1&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "option_id"

  Scenario: Create variable product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "VARIABLE-PRODUCT",
        "templateId": "@product_template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "variable_product_id"

  Scenario: Add bind attribute
    When I send a POST request to "/api/v1/en_GB/products/@variable_product_id@/binding" with body:
      """
      {
        "bind_id": "@attribute_id@"
      }
      """
    Then the response status code should be 201

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

  Scenario: Edit product select value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@simple_product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": "@option_id@"
        }
      """
    Then the response status code should be 200

  Scenario: Add children product
    When I send a POST request to "/api/v1/en_GB/products/@variable_product_id@/children" with body:
      """
      {
        "child_id": "@simple_product_id@"
      }
      """
    Then the response status code should be 204

  Scenario: Create batch action with releated simple product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
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
    And store response param "id" as "batch_action_1_id"

  Scenario: Get not exists batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@"
    Then the response status code should be 200
    And the JSON node "entries[0].id" should exist

  Scenario: Create batch action with related simple product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
        "filter": {
          "ids": {
            "list": [
              "@variable_product_id@"
            ],
            "included": true
          }
        }
      }
    """

    Then the response status code should be 201
    And store response param "id" as "batch_action_2_id"

  Scenario: Create batch action with none related simple product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_DELETE",
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
    And store response param "id" as "batch_action_3_id"

  Scenario: Get not exists batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_3_id@"
    Then the response status code should be 200
    And the JSON node "entries[0].id" should not exist
