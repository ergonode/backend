Feature: Draft edit and inheritance value for product draft with text area attribute

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
        "sku": "SKU_@@random_code@@",
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Edit product text value in "en_GB" language (rte)
    When I send a PUT request to "api/v1/en_GB/products/@product_id@/draft/@attribute_id_rte@/value" with body:
      """
      {
        "value": "text attribute value rte in english"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value in "pl_PL" language (rte)
    When I send a PUT request to "api/v1/pl_PL/products/@product_id@/draft/@attribute_id_rte@/value" with body:
      """
      {
        "value": "text attribute value rte in polish"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value in "de_DE" language (rte)
    When I send a PUT request to "api/v1/de_DE/products/@product_id@/draft/@attribute_id_rte@/value" with body:
      """
      {
        "value": null
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value in "en_GB" language (no rte)
    When I send a PUT request to "api/v1/en_GB/products/@product_id@/draft/@attribute_id_norte@/value" with body:
      """
      {
        "value": "text attribute value norte in english"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value in "pl_PL" language (no rte)
    When I send a PUT request to "api/v1/pl_PL/products/@product_id@/draft/@attribute_id_norte@/value" with body:
      """
      {
        "value": "text attribute value norte in polish"
      }
      """
    Then the response status code should be 200

  Scenario: Edit product text value in "de_DE" language (no rte)
    When I send a PUT request to "api/v1/de_DE/products/@product_id@/draft/@attribute_id_norte@/value" with body:
      """
      {
        "value": null
      }
      """
    Then the response status code should be 200

  Scenario: Get draft values in "de_DE" language
    When I send a GET request to "api/v1/de_DE/products/@product_id@/draft"
    Then the response status code should be 200
    And the JSON node "attributes.@attribute_code_rte@" should be null
    And the JSON node "attributes.@attribute_code_norte@" should be null

  Scenario: Get draft values in "pl_PL" language
    When I send a GET request to "api/v1/pl_PL/products/@product_id@/draft"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | text attribute value rte in polish |
      | attributes.@attribute_code_norte@ | text attribute value norte in polish |

  Scenario: Get draft values in "en_GB" language
    When I send a GET request to "api/v1/en_GB/products/@product_id@/draft"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | text attribute value rte in english |
      | attributes.@attribute_code_norte@ | text attribute value norte in english |

  Scenario: Get draft values in "fr_FR" language
    When I send a GET request to "api/v1/fr_FR/products/@product_id@/draft"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | text attribute value rte in english |
      | attributes.@attribute_code_norte@ | text attribute value norte in english |

  Scenario: Remove value for "pl_PL" language (rte)
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/draft/@attribute_id_rte@/value"
    Then the response status code should be 204

  Scenario: Remove value for "pl_PL" language (norte)
    When I send a DELETE request to "api/v1/pl_PL/products/@product_id@/draft/@attribute_id_norte@/value"
    Then the response status code should be 204

  Scenario: Get draft values in "pl_PL" language after remove pl value (get inheritance value)
    When I send a GET request to "api/v1/pl_PL/products/@product_id@/draft"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | attributes.@attribute_code_rte@ | text attribute value rte in english |
      | attributes.@attribute_code_norte@ | text attribute value norte in english |
