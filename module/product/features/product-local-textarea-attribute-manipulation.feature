Feature: Product edit and inheritance value for product product with textarea attribute

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

  Scenario: Create textarea attribute (rte)
    Given remember param "attribute_code_rte" with value "textarea_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code_rte@",
        "type": "TEXT_AREA",
        "scope": "local",
        "groups": [],
         "parameters":
          {
          "richEdit": true
          }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id_rte"

  Scenario: Create textarea attribute (no rte)
    Given remember param "attribute_code_norte" with value "textarea_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code_norte@",
        "type": "TEXT_AREA",
        "scope": "local",
        "groups": [],
         "parameters":
          {
          "richEdit": false
          }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id_norte"

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

  Scenario: Edit product textarea value in "en_GB" language (norte)
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id_norte@" with body:
      """
        {
          "value": "textarea attribute value norte in english"
        }
      """
    Then the response status code should be 200

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_norte@ | textarea attribute value norte in english |

  Scenario: Delete product  textarea value in "en_GB" language
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id_norte@"
    Then the response status code should be 204

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code_norte@" should not exist

  Scenario: Edit product textarea value in "en_GB", "pl_PL" and "de_DE" language (rte) (batch endpoint)
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
        {
          "data": [
           {
              "id": "@product_id@",
              "payload": [
                {
                  "id": "@attribute_id_rte@",
                  "values" : [
                    {
                      "language": "en_GB",
                      "value": "textarea attribute value rte in english (batch)"
                    },
                     {
                      "language": "pl_PL",
                       "value": "textarea attribute value rte in polish (batch)"
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

  Scenario: Edit product textarea value in "en_GB", "pl_PL" and "de_DE" language (no rte) (batch endpoint)
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
        {
          "data": [
           {
              "id": "@product_id@",
              "payload": [
                {
                  "id": "@attribute_id_norte@",
                  "values" : [
                    {
                      "language": "en_GB",
                      "value": "textarea attribute value norte in english (batch)"
                    },
                     {
                      "language": "pl_PL",
                       "value": "textarea attribute value norte in polish (batch)"
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
    And the JSON node "attributes.@attribute_code_rte@" should be null
    And the JSON node "attributes.@attribute_code_norte@" should be null

  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in polish (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in polish (batch) |

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in english (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in english (batch) |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in english (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in english (batch) |

  Scenario: Remove value for "pl_PL" language (rte)
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id_rte@"
    Then the response status code should be 204

  Scenario: Remove value for "pl_PL" language (norte)
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id_norte@"
    Then the response status code should be 204

  Scenario: Get product values in "pl_PL" language after remove pl value (get inheritance value)
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in english (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in english (batch) |
