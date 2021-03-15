Feature: Batch action get templates

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template 1
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id_1"

  Scenario: Create template 2
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id_2"

  Scenario: Create template 3
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id_3"


  Scenario: Create product 1
    And remember param "product_sku_1" with value "SKU_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "@product_sku_1@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id_1@",
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
        "templateId": "@product_template_id_2@",
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
        "templateId": "@product_template_id_3@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_ 3"

  Scenario: Get templates excluded with Id
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
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
    And the JSON node "[0]" should exist
    And the JSON node "[1]" should exist

  Scenario: Get templates with no filter
    When I send a POST request to "/api/v1/en_GB/batch-action/templates"
    Then the response status code should be 400

  Scenario: Get templates excluded without Id
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
        "filter": "all"
      }
      """
    Then the response status code should be 200
    And the JSON node "[0]" should exist
    And the JSON node "[1]" should exist


  Scenario: Get templates included
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
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
      | [0] | @product_template_id_1@ |
    And the JSON node "[1]" should not exist

  Scenario: Get templates included and query
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
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
      | [0] | @product_template_id_1@ |
      | [1] | @product_template_id_2@ |
    And the JSON node "[2]" should not exist

  Scenario: Get templates excluded and query
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
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
    And the JSON node "[0]" should exist

  Scenario: Get templates by query
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
        "filter": {
          "query": "sku=@product_sku_1@"
        }
      }
      """
    Then the response status code should be 200
      | [0] | @product_template_id_1@ |
    And the JSON node "[1]" should not exist

  Scenario: Get templates with no products
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
        "filter": {
          "ids": {
            "list": [
              null
            ],
            "included": false
          }
        }
      }
      """
    Then the response status code should be 400

  Scenario: Get templates with not bool included
    When I send a POST request to "/api/v1/en_GB/batch-action/templates" with body:
      """
      {
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
