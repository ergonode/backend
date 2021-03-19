Feature: Product edit and inheritance value for product product with unit attribute

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
          "languages":
            {
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

  Scenario: Create unit object 1
    And I send a "POST" request to "/api/v1/en_GB/units" with body:
      """
      {
        "name": "@@random_md5@@",
        "symbol": "@@random_symbol@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "unit_id"

  Scenario: Create unit attribute
    Given remember param "attribute_code" with value "unit_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code@",
        "type": "UNIT",
        "scope": "local",
        "groups": [],
        "parameters":
        {
          "unit": "@unit_id@"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "template_id"

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

  Scenario: Edit product unit value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": "300.11"
        }
      """
    Then the response status code should be 200

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 300.11 |

  Scenario: Delete product  unit value in "en_GB" language
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id@"
    Then the response status code should be 204

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code@" should not exist

  Scenario: Edit product unit value in "en_GB", "pl_PL" and "de_DE" language (batch endpoint)
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
                      "value": "100.99"
                    },
                     {
                      "language": "pl_PL",
                       "value": "200.99"
                    },
                    {
                      "language": "de_DE",
                       "value": null
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 200

  Scenario: Edit product unit value in "en_GB" language (batch endpoint) - wrong value
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
                      "value": "text"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 500
    And the JSON node "exception.current.message" should contain "Expected a numeric. Got: string"


  Scenario: Get product values in "de_DE" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/de_DE"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code@" should be null

  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 200.99 |

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 100.99 |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 100.99 |

  Scenario: Remove value for "pl_PL" language
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id@"
    Then the response status code should be 204

  Scenario: Get product values in "pl_PL" language after remove pl value (get inheritance value)
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 100.99 |
