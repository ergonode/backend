Feature: Batch Action change Product Attribute Value

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    When remember param "text_attribute_code" with value "text_@@random_code@@"
    And I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "@text_attribute_code@",
          "type": "TEXT",
          "scope": "local"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "text_attribute_id"

  Scenario: Create multi-select attribute
    When remember param "multi_select_code" with value "multi_select_@@random_code@@"
    And I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@multi_select_code@",
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
      | 4  | Option pl 4 | Option en 4 |
      | 5  | Option pl 5 | Option en 5 |

  Scenario Outline: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_<lp>"
    Examples:
      | lp |
      | 1  |
      | 2  |
      | 3  |
      | 4  |
      | 5  |


  Scenario Outline:  Create products
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "<type>",
        "templateId": "<template>"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_<lp>"
    Examples:
      | lp | template             | type           |
      | 1  | @product_template_1@ | SIMPLE-PRODUCT |
      | 2  | @product_template_2@ | SIMPLE-PRODUCT |
      | 3  | @product_template_3@ | SIMPLE-PRODUCT |
      | 4  | @product_template_4@ | SIMPLE-PRODUCT |
      | 5  | @product_template_5@ | SIMPLE-PRODUCT |


  Scenario: Create batch action with exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_EDIT",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@",
              "@product_id_3@"
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
                "value": "test_batch-action_include_PL"
              },
              {
                "language": "en_GB",
                "value": "test_batch-action_include_GB"
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
    And store response param "id" as "batch_action_id_1"

  Scenario: Get batch action
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_id_1@"
    Then the response status code should be 200

  Scenario: Get batch action entry grid
    And I send a "GET" request to "/api/v1/en_GB/batch-action/@batch_action_id_1@/entries"
    Then the response status code should be 200

  Scenario Outline: Get product
    And I send a "GET" request to "/api/v1/en_GB/products/<product_id>"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                                   | SIMPLE-PRODUCT                                      |
      | attributes.@text_attribute_code@.en_GB | test_batch-action_include_GB                        |
      | attributes.@text_attribute_code@.pl_PL | test_batch-action_include_PL                        |
      | attributes.@multi_select_code@.en_GB   | @multiselect_option_id_2@,@multiselect_option_id_3@ |
      | attributes.@multi_select_code@.pl_PL   | @multiselect_option_id_1@,@multiselect_option_id_2@ |
    Examples:
      | product_id     |
      | @product_id_1@ |
      | @product_id_3@ |

  Scenario: Create batch action with exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_EDIT",
        "filter": {
          "ids": {
            "list": [
              "@product_id_1@",
              "@product_id_2@"
            ],
            "included": false
          }
        },
        "payload": [
          {
            "id": "@text_attribute_id@",
            "values": [
              {
                "language": "pl_PL",
                "value": "test_batch-action_exclude_PL"
              },
              {
                "language": "en_GB",
                "value": "test_batch-action_exclude_GB"
              }
            ]
          },
          {
            "id": "@multiselect_attribute_id@",
            "values": [
              {
                "language": "pl_PL",
                "value": [
                  "@multiselect_option_id_4@",
                  "@multiselect_option_id_5@"
                ]
              },
              {
                "language": "en_GB",
                "value": [
                  "@multiselect_option_id_4@",
                  "@multiselect_option_id_5@"
                ]
              }
            ]
          }
        ]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_id_2"


  Scenario: Get product
    And I send a "GET" request to "/api/v1/en_GB/products/@product_id_1@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                                   | SIMPLE-PRODUCT                                      |
      | attributes.@text_attribute_code@.en_GB | test_batch-action_include_GB                        |
      | attributes.@text_attribute_code@.pl_PL | test_batch-action_include_PL                        |
      | attributes.@multi_select_code@.en_GB   | @multiselect_option_id_2@,@multiselect_option_id_3@ |
      | attributes.@multi_select_code@.pl_PL   | @multiselect_option_id_1@,@multiselect_option_id_2@ |

  Scenario Outline: Get product
    And I send a "GET" request to "/api/v1/en_GB/products/<product_id>"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                                   | SIMPLE-PRODUCT                                      |
      | attributes.@text_attribute_code@.en_GB | test_batch-action_exclude_GB                        |
      | attributes.@text_attribute_code@.pl_PL | test_batch-action_exclude_PL                        |
      | attributes.@multi_select_code@.en_GB   | @multiselect_option_id_4@,@multiselect_option_id_5@ |
      | attributes.@multi_select_code@.pl_PL   | @multiselect_option_id_4@,@multiselect_option_id_5@ |
    Examples:
      | product_id     |
      | @product_id_3@ |
      | @product_id_4@ |
      | @product_id_5@ |


  Scenario: Create batch action with exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_EDIT",
        "filter": {
          "query": "esa_template:en_GB=@product_template_4@,@product_template_5@"
        },
        "payload": [
          {
            "id": "@text_attribute_id@",
            "values": [
              {
                "language": "pl_PL",
                "value": "test_batch-action_template_PL"
              },
              {
                "language": "en_GB",
                "value": "test_batch-action_template_GB"
              }
            ]
          }
        ]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_id_3"

  Scenario Outline: Get product
    And I send a "GET" request to "/api/v1/en_GB/products/<product_id>"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                                   | SIMPLE-PRODUCT                |
      | attributes.@text_attribute_code@.en_GB | test_batch-action_template_GB |
      | attributes.@text_attribute_code@.pl_PL | test_batch-action_template_PL |
    Examples:
      | product_id     |
      | @product_id_4@ |
      | @product_id_5@ |


  Scenario: Create batch action with exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_EDIT",
        "filter": {
          "query": "esa_template:en_GB=@product_template_2@,@product_template_5@",
          "ids": {
            "list": [
              "@product_id_1@"
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
                "value": "test_batch-action_template_included_PL"
              },
              {
                "language": "en_GB",
                "value": "test_batch-action_template_included_GB"
              }
            ]
          }
        ]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_id_4"

  Scenario Outline: Get product
    And I send a "GET" request to "/api/v1/en_GB/products/<product_id>"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                                   | SIMPLE-PRODUCT                         |
      | attributes.@text_attribute_code@.en_GB | test_batch-action_template_included_GB |
      | attributes.@text_attribute_code@.pl_PL | test_batch-action_template_included_PL |
    Examples:
      | product_id     |
      | @product_id_1@ |
      | @product_id_2@ |
      | @product_id_5@ |

  Scenario: Create batch action with exists product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
        "type": "PRODUCT_EDIT",
        "filter": {
          "query": "esa_template:en_GB=@product_template_2@,@product_template_5@",
          "ids": {
            "list": [
              "@product_id_2@"
            ],
            "included": false
          }
        },
        "payload": [
          {
            "id": "@text_attribute_id@",
            "values": [
              {
                "language": "pl_PL",
                "value": "test_batch-action_template_exclude_PL"
              },
              {
                "language": "en_GB",
                "value": "test_batch-action_template_exclude_GB"
              }
            ]
          }
        ]
      }
    """
    Then the response status code should be 201
    And store response param "id" as "batch_action_id_5"

  Scenario Outline: Get product
    And I send a "GET" request to "/api/v1/en_GB/products/<product_id>"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | type                                   | SIMPLE-PRODUCT                        |
      | attributes.@text_attribute_code@.en_GB | test_batch-action_template_exclude_GB |
      | attributes.@text_attribute_code@.pl_PL | test_batch-action_template_exclude_PL |
    Examples:
      | product_id     |
      | @product_id_5@ |
