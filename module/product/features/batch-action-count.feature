Feature: Batch action get templates

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

  Scenario: Create product 1
    And remember param "product_sku_1" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku_1@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_1"

  Scenario: Create product 2
    And remember param "product_sku_2" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku_2@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_2"

  Scenario: Create product 3
    And remember param "product_sku_3" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku_3@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_ 3"

  Scenario: Count with excluded id
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@"
            ],
            "included": false
          }
        }
      }
      """
    Then the response status code should be 200
    And the JSON node count should exist

  Scenario: Count with no filter
    When I send a POST request to "/api/v1/en_GB/batch-action/count"
    Then the response status code should be 400

  Scenario: Count all
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": "all"
      }
      """
    Then the response status code should be 200
    And the JSON node count should exist

  Scenario: Count with included id
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
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
    Then the response status code should be 200
    And the JSON node count should exist

  Scenario: Count with included id and query
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@"
            ],
            "included": true
          },
          "query": "sku=@product_sku_2@"
        }
      }
      """
    Then the response status code should be 200
    And the JSON node count should exist

  Scenario: Count with excluded id and query
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@"
            ],
            "included": false
          },
          "query": "sku=@product_sku_2@"
        }
      }
      """
    Then the response status code should be 200
    And the JSON node count should exist

  Scenario: Count by query
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": {
          "query": "sku=@product_sku_2@"
        }
      }
      """
    Then the response status code should be 200
    And the JSON node count should exist

  Scenario: Count with empty ids list
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": {
          "ids": {
            "list": [
              null
            ],
            "included": false
          },
          "query": "sku=@product_sku_2@"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Count not determining included
    When I send a POST request to "/api/v1/en_GB/batch-action/count" with body:
      """
      {
        "type": "product_edit",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@"
            ],
            "included": "test"
          }
        }
      }
      """
    Then the response status code should be 400
