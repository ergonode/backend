Feature: Product edit and inheritance value for product product with numeric attribute

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get language en
    When I send a GET request to "/api/v1/en_GB/languages/en_GB"
    Then the response status code should be 200
    And store response param "id" as "language_id_en"

  Scenario: Get language pl
    When I send a GET request to "/api/v1/en_GB/languages/pl_PL"
    Then the response status code should be 200
    And store response param "id" as "language_id_pl"

  Scenario: Get language fr
    When I send a GET request to "/api/v1/en_GB/languages/fr_FR"
    Then the response status code should be 200
    And store response param "id" as "language_id_fr"

  Scenario: Get language de
    When I send a GET request to "/api/v1/en_GB/languages/de_DE"
    Then the response status code should be 200
    And store response param "id" as "language_id_de"

  Scenario: Activate languages
    When I send a PUT request to "api/v1/en_GB/languages" with body:
      """
      {
        "collection": [
          "en_GB","pl_PL", "fr_FR", "de_DE"
        ]
      }
      """
    Then the response status code should be 204

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

  Scenario: Create numeric attribute
    Given remember param "attribute_code" with value "numeric_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code@",
        "type": "NUMERIC",
        "scope": "local",
        "groups": []
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

  Scenario: Edit product numeric value in "en_GB", "pl_PL" and "de_DE" language
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
                      "value": "100"
                    },
                     {
                      "language": "pl_PL",
                       "value": "200"
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

  Scenario: Get product values in "de_DE" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/de_DE"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code@" should be null


  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 200 |

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 100 |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 100 |

  Scenario: Remove value for "pl_PL" language
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id@"
    Then the response status code should be 204

  Scenario: Get product values in "pl_PL" language after remove pl value (get inheritance value)
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@ | 100 |
