Feature: Product edit and inheritance value for product product with product relation attribute

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

  Scenario: Create product relation attribute
    Given remember param "attribute_code" with value "product_relation_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code@",
        "type": "PRODUCT_RELATION",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create second product relation attribute
    Given remember param "attribute_code_2" with value "product_relation_@@random_code@@"
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "@attribute_code_2@",
        "type": "PRODUCT_RELATION",
        "scope": "global",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_2_id"

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
    And store response param "id" as "product_1_id"

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
    And store response param "id" as "product_2_id"

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
    And store response param "id" as "product_3_id"

  Scenario: Check valid product relation attribute validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate" with body:
      """
      {
        "value": ["@product_2_id@"]
      }
      """
    Then the response status code should be 200

  Scenario: Check valid empty array product relation attribute validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate" with body:
      """
      {
        "value": []
      }
      """
    Then the response status code should be 200


  Scenario: Check parameter random uuid validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate?aggregateId=@@random_uuid@@" with body:
      """
      {
        "value": ["@product_2_id@"]
      }
      """
    Then the response status code should be 400

  Scenario: Check parameter string validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate?aggregateId=test" with body:
      """
      {
        "value": ["@product_2_id@"]
      }
      """
    Then the response status code should be 400

  Scenario: Check invalid Uuid product relation attribute validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate?aggregateId=@product_1_id@" with body:
      """
      {
        "value": ["13123"]
      }
      """
    Then the response status code should be 400

  Scenario: Check not exists product product relation attribute validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate?aggregateId=@product_1_id@" with body:
      """
      {
        "value": ["@@random_uuid@@"]
      }
      """
    Then the response status code should be 400

  Scenario: Check add product relation with the same product validation
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate?aggregateId=@product_1_id@" with body:
      """
      {
        "value": ["@product_1_id@"]
      }
      """
    Then the response status code should be 400

  Scenario: Edit product product relation value in "en_GB" and "pl_PL" language (batch endpoint)
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
                      "value": ["@product_3_id@", "@product_2_id@"]
                    },
                     {
                      "language": "en_GB",
                       "value": ["@product_1_id@"]
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 204


  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@[0] | @product_3_id@ |
    And the JSON node "attributes.@attribute_code@[2]" should not exist

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@[0] | @product_1_id@ |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@[0] | @product_1_id@ |

  Scenario: Edit product relation value in "en_GB" language
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": ["@product_2_id@"]
        }
      """
    Then the response status code should be 200

  Scenario: Check add product relation with the same product validation
    When I send a PUT request to "/api/v1/en_GB/products/@product_id@/attribute/@attribute_id@" with body:
      """
        {
          "value": ["@product_id@"]
        }
      """
    Then the response status code should be 400

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code@[0] | @product_2_id@ |

  Scenario: Remove product which is related to other
    When I send a DELETE request to "/api/v1/en_GB/products/@product_2_id@"
    Then the response status code should be 409

  Scenario: Remove product with relations
    When I send a DELETE request to "/api/v1/en_GB/products/@product_id@"
    Then the response status code should be 204

  Scenario: Remove product 1 with is not related to other
    When I send a DELETE request to "/api/v1/en_GB/products/@product_1_id@"
    Then the response status code should be 204

  Scenario: Remove product 2 with is not related to other
    When I send a DELETE request to "/api/v1/en_GB/products/@product_2_id@"
    Then the response status code should be 204

  Scenario: Remove relation attribute
    When I send a DELETE request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
