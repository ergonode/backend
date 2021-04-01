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

  Scenario: Update Tree
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
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
        "scope": "global",
        "groups": [],
        "parameters":
         {
          "richEdit": true
          }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id_rte"

  Scenario: Create textarea attribute (norte)
    Given remember param "attribute_code_norte" with value "textarea_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code_norte@",
        "type": "TEXT_AREA",
        "scope": "global",
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

  Scenario: Edit product textarea value in "en_GB" language (norte) (batch endpoint)
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
                    }
                  ]
                },
                {
                  "id": "@attribute_id_rte@",
                  "values" : [
                    {
                      "language": "en_GB",
                       "value": "textarea attribute value rte in english (batch)"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 200

  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in english (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in english (batch) |

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
    Then the response status code should be 403

  Scenario: Remove value for "pl_PL" language (norte)
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id_norte@"
    Then the response status code should be 403

  Scenario: Edit product textarea value in "en_GB" language (norte) (batch endpoint)
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
                       "value": "textarea attribute value norte in polish (batch)"
                    }
                  ]
                },
                {
                  "id": "@attribute_id_rte@",
                  "values" : [
                    {
                      "language": "en_GB",
                       "value": "textarea attribute value rte in polish (batch)"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 200

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in polish (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in polish (batch) |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | textarea attribute value rte in polish (batch) |
      | attributes.@attribute_code_norte@ | textarea attribute value norte in polish (batch) |
