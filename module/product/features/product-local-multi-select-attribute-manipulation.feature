Feature: Product edit and inheritance value for product product with multi-select attribute

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario Outline: Get language <language>
    When I send a GET request to "/api/v1/en_GB/languages/<language>"
    Then the response status code should be 200
    And store response param "id" as "<id>"
    Examples:
      | language | id             |
      | en_GB    | language_id_en |
      | pl_PL    | language_id_pl |
      | fr_FR    | language_id_fr |
      | de_DE    | language_id_de |

  Scenario: Update Tree
    When I send a PUT request to "/api/v1/en_GB/language/tree" with body:
      """
        {
          "languages": {
            "language_id":"@language_id_en@",
            "children":[
              {
                "language_id":"@language_id_pl@",
                "children":[]
              },
              {
                "language_id":"@language_id_fr@",
                "children":[]
              },
              {
                "language_id":"@language_id_de@",
                "children":[]
              }
            ]
          }
        }
      """
    Then the response status code should be 204

  Scenario: Get attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=multiselect_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id"

  Scenario Outline:  Get option <key> id
    When I send a GET request to "/api/v1/en_GB/attributes/@attribute_id@/options/grid?filter=code=<key>&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "<key>_id"
    Examples:
      | key      |
      | option_1 |
      | option_2 |
      | option_3 |

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_id"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "type": "SIMPLE-PRODUCT",
        "sku": "SKU_@@random_code@@",
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Edit product multi-select value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": ["@option_3_id@"]
        }
      """
    Then the response status code should be 200

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.multiselect_attribute_local[0] | @option_3_id@ |

    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"

  Scenario: Delete product  multi-select value in "en_GB" language
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id@"
    Then the response status code should be 204

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON node "attributes.multiselect_attribute_local" should not exist

  Scenario: Edit product multi-select value in "en_GB" and "pl_PL" language (batch endpoint)
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
                      "value": ["@option_2_id@"]
                    },
                     {
                      "language": "en_GB",
                       "value": ["@option_1_id@"]
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 204

  Scenario: Edit product multi-select value in "en_GB" language (batch endpoint) (value not an array)
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
                      "language": "en_GB",
                       "value": ["@option_1_id@"]
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 204

  Scenario: Edit product multi-select value in "en_GB" language (batch endpoint) (value not uuid)
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
                      "language": "en_GB",
                       "value": ["test"]
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 400
    And the JSON node "errors.value" should contain "One or more of the given values is invalid."

  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.multiselect_attribute_local[0] | @option_2_id@ |

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.multiselect_attribute_local[0] | @option_1_id@ |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.multiselect_attribute_local[0] | @option_1_id@ |

  Scenario: Remove value for "pl_PL" language
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id@"
    Then the response status code should be 204

  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.multiselect_attribute_local[0] | @option_1_id@ |
