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
    And store response param "id" as "text_attribute_id"

  Scenario: Create multi-select attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "multi_select_@@random_code@@",
        "type": "MULTI_SELECT",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multiselect_attribute_id"

  Scenario Outline: Create option for multi-select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@multiselect_attribute_id@/options" with body:
      """
      {
        "code": "option_@@random_code@@",
        "label":  {
          "pl_PL": "<pl>",
          "en_GB": "<en>"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multiselect_option_id_<lp>"
    Examples:
      | lp | pl          | en          |
      | 1  | Option pl 1 | Option en 1 |
      | 2  | Option pl 2 | Option en 2 |
      | 3  | Option pl 3 | Option en 3 |

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
            "id": "@text_attribute_id@",
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
          },
          {
            "id": "@multiselect_attribute_id@",
            "values": [
              {
                "language": "pl_PL",
                "value": [
                  "@multiselect_option_id_1@",
                  "@multiselect_option_id_2@"
                ]
              },
              {
                "language": "en_GB",
                "value": [
                  "@multiselect_option_id_2@",
                  "@multiselect_option_id_3@"
                ]
              }
            ]
          }
        ]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_1_id"

  Scenario: Get batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@"
    Then the response status code should be 200

  Scenario: Get batch action entry grid
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_1_id@/entries"
    Then the response status code should be 200
