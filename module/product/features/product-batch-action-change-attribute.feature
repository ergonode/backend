Feature: Batch Action change Product Attribute Value

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Create batch action with exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_EDIT",
        "filter": {
          "ids": {
            "list": [
              "@product_id@"
            ],
            "included": true
          }
        },
        "payload": [
          {
            "id": "@attribute_id@",
            "values": [
              {
                "language": "pl_PL",
                "value": "test_batch-action_PL"
              },
              {
                "language": "en_GB",
                "value": "test_batch-action_GB"
              }
            ]
          }
        ]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Get not exists batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@"
    Then the response status code should be 200
