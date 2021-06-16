Feature: Product module

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

  Scenario: Get attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And store response param "code" as "attribute_code"

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
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario Outline: Update product attributes with product
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
        {
          "data": [
           {
              "id": <product_id>,
              "payload": [
                {
                  "id": <attribute_id>,
                  "values" : [
                    {
                      "language": <language>,
                      "value": "test"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 400
    And the JSON node <error> should exist
    Examples:
      | product_id        | attribute_id      | language | error                                                               |
      | null              | "@attribute_id@"  | "en_EN"  | "errors.data.element-0.id"                                          |
      | "NOT UUID"        | "@attribute_id@"  | "en_EN"  | "errors.data.element-0.id"                                          |
      | "@@random_uuid@@" | "@attribute_id@"  | "en_EN"  | "errors.data.element-0.id"                                          |
      | "@product_id@"    | null              | "en_EN"  | "errors.data.element-0.payload.element-0.id"                        |
      | "@product_id@"    | "NOT UUID"        | "en_EN"  | "errors.data.element-0.payload.element-0.id"                        |
      | "@product_id@"    | "@@random_uuid@@" | "en_EN"  | "errors.data.element-0.payload.element-0.id"                        |
      | "@product_id@"    | "@attribute_id@"  | null     | "errors.data.element-0.payload.element-0.values.element-0.language" |
      | "@product_id@"    | "@attribute_id@"  | "BAD"    | "errors.data.element-0.payload.element-0.values.element-0.language" |

  Scenario: Update attributes
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_id@",
            "payload": [
              {
                "id": "@attribute_id@",
                "values" : [
                  {
                    "language": "pl_PL",
                    "value": "test_PL"
                  },
                  {
                    "language": "en_GB",
                    "value": "test_GB"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | attributes.@attribute_code@.pl_PL | test_PL |
      | attributes.@attribute_code@.en_GB | test_GB |

  Scenario: Update attributes with null value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_id@",
            "payload": [
              {
                "id": "@attribute_id@",
                "values" : [
                  {
                    "language": "pl_PL",
                    "value": "test_PL"
                  },
                  {
                    "language": "en_GB",
                    "value": null
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code@.en_GB" should be null
    And the JSON nodes should contain:
      | attributes.@attribute_code@.pl_PL | test_PL |

  Scenario: Delete product attributes pl_PL language
    When I send a DELETE request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_id@",
            "payload": [
              {
                "id": "@attribute_id@",
                "languages": ["pl_PL"]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code@.en_GB" should be null
    And the JSON node "attributes.@attribute_code@.pl_PL" should not exist

  Scenario: Delete product attributes en_GB language
    When I send a DELETE request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@product_id@",
            "payload": [
              {
                "id": "@attribute_id@",
                "languages": ["en_GB"]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get product
    When I send a GET request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code@" should not exist

  Scenario Outline: Delete product attributes with product
    When I send a DELETE request to "/api/v1/en_GB/products/attributes" with body:
      """
        {
          "data": [
           {
              "id": <product_id>,
              "payload": [
                {
                  "id": <attribute_id>,
                  "languages" : [<language>]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 400
    And the JSON node <error> should exist
    Examples:
      | product_id        | attribute_id      | language | error                                                         |
      | null              | "@attribute_id@"  | "en_EN"  | "errors.data.element-0.id"                                    |
      | "NOT UUID"        | "@attribute_id@"  | "en_EN"  | "errors.data.element-0.id"                                    |
      | "@@random_uuid@@" | "@attribute_id@"  | "en_EN"  | "errors.data.element-0.id"                                    |
      | "@product_id@"    | null              | "en_EN"  | "errors.data.element-0.payload.element-0.id"                  |
      | "@product_id@"    | "NOT UUID"        | "en_EN"  | "errors.data.element-0.payload.element-0.id"                  |
      | "@product_id@"    | "@@random_uuid@@" | "en_EN"  | "errors.data.element-0.payload.element-0.id"                  |
      | "@product_id@"    | "@attribute_id@"  | null     | "errors.data.element-0.payload.element-0.languages.element-0" |
      | "@product_id@"    | "@attribute_id@"  | "BAD"    | "errors.data.element-0.payload.element-0.languages.element-0" |