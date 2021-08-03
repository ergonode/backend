Feature: Completeness module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_id"

  Scenario: Get text attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=text_attribute_global&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id_1"

  Scenario: Get numeric attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=numeric_attribute_global&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id_2"

  Scenario: Get price attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=price_attribute_global&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "attribute_id_3"

  Scenario: Update template
    When I send a PUT request to "/api/v1/en_GB/templates/@template_id@" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": [
          {
            "position": {"x": 10, "y": 10},
            "size": {"width": 2, "height": 2},
            "type": "text",
            "properties": {
              "attribute_id": "@attribute_id_1@",
              "required": true
            }
          },
          {
            "position": {"x": 1, "y": 1},
            "size": {"width": 2, "height": 2},
            "type": "text",
            "properties": {
              "attribute_id": "@attribute_id_2@",
              "required": true
            }
          },
              {
            "position": {"x": 3, "y": 3},
            "size": {"width": 2, "height": 2},
            "type": "text",
            "properties": {
              "attribute_id": "@attribute_id_3@",
              "required": false
            }
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Create simple product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

  Scenario: Edit product text value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
        {
          "data": [
           {
              "id": "@product_id@",
              "payload": [
                {
                  "id": "@attribute_id_1@",
                  "values" : [
                    {
                      "language": "en_GB",
                      "value": "text attribute value in english"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 204

  Scenario: Get Completeness with wrong product id
    When I send a GET request to "/api/v1/en_GB/products/@@random_uuid@@/completeness"
    Then the response status code should be 404

  Scenario: Get Completeness
    When I send a GET request to "/api/v1/en_GB/products/@product_id@/completeness"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | percent       | 50               |
      | required      | 2                |
      | filled        | 1                |
      | missing[0].id | @attribute_id_2@ |
