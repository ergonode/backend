Feature: Product edit and inheritance value for product product with gallery attribute

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

  Scenario: Get attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=gallery_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id"

  Scenario: Upload new first multimedia file
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_1_id"

  Scenario: Upload new first multimedia file
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia.jpg |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_2_id"

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

  Scenario: Edit product gallery value in "en_GB" and "pl_PL" language (batch endpoint)
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
                      "value": ["@multimedia_2_id@"]
                    },
                     {
                      "language": "en_GB",
                       "value": ["@multimedia_1_id@"]
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 204

  Scenario: Edit product gallery value in "en_GB" language (batch endpoint) (value not an array)
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
                       "value": "@multimedia_1_id@"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 400

  Scenario: Edit product gallery value in "en_GB" language (batch endpoint) (value not uuid)
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
    And the JSON node "errors.value" should contain "Multimedia test not exists."

  Scenario: Get product values in "pl_PL" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.gallery_attribute_local[0] | @multimedia_2_id@ |

  Scenario: Get product values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/en_GB"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.gallery_attribute_local[0] | @multimedia_1_id@ |

  Scenario: Get product values in "fr_FR" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/fr_FR"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.gallery_attribute_local[0] | @multimedia_1_id@ |

  Scenario: Edit product gallery value in "de_DE" language (batch endpoint)
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
                      "language": "de_DE",
                      "value": []
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 204

  Scenario: Remove value for "pl_PL" language
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/attribute/@attribute_id@"
    Then the response status code should be 204

  Scenario: Get product values in "pl_PL" language after remove pl value (get inheritance value)
    When I send a GET request to "api/v1/en_GB/products/@product_id@/inherited/pl_PL"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.gallery_attribute_local[0] | @multimedia_1_id@ |
