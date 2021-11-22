Feature: Product edit feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get text attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=text_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_text_attribute"

  Scenario: Get textarea attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=textarea_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_textarea_attribute"

  Scenario: Get select attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=select_attribute_local;type=SELECT&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_select_attribute"

  Scenario:  et select option 1 id
    When I send a GET request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options/grid?filter=code=option_1&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "select_option_1_id"

  Scenario: Get select option 2 id
    When I send a GET request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options/grid?filter=code=option_2&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "select_option_2_id"

  Scenario: Get multiselect attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=multiselect_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_multiselect_attribute"

  Scenario:  et select option 1 id
    When I send a GET request to "/api/v1/en_GB/attributes/@product_edit_multiselect_attribute@/options/grid?filter=code=option_1&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "multiselect_option_1_id"

  Scenario: Get select option 2 id
    When I send a GET request to "/api/v1/en_GB/attributes/@product_edit_multiselect_attribute@/options/grid?filter=code=option_2&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "multiselect_option_2_id"

  Scenario: Get unit attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=unit_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_unit_attribute"

  Scenario: Get price attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=unit_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_price_attribute"

  Scenario: Get date attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=date_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_date_attribute"

  Scenario: Get template id
    When I send a GET request to "/api/v1/en_GB/templates?filter=name=Template&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "product_edit_template"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "edit_product"

  Scenario: Get statuses
    When I send a GET request to "/api/v1/en_GB/workflow/default/transitions"
    Then the response status code should be 200
    And store response param "collection[0].from" as "from_status_id"
    And store response param "collection[0].to" as "to_status_id"

  Scenario: Get esa_status id
    When I send a GET request to "/api/v1/en_GB/attributes/system?limit=50&offset=0&filter=code%3Desa_status"
    Then the response status code should be 200
    And store response param "collection[0].id" as "esa_status_id"


  Scenario: Edit product text value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_text_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "text attribute value"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Assign select attribute to product
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_textarea_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "textarea attribute value"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit product select value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_select_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "@select_option_1_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit product multi select value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_multiselect_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": ["@multiselect_option_1_id@", "@multiselect_option_2_id@"]
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit product unit value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_unit_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "102030"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit product price value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_price_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                     "value": "9999.99"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit product date value
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@product_edit_date_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "2019-12-30"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit status in pl_PL
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@esa_status_id@",
                "values" : [
                  {
                    "language": "pl_PL",
                    "value": "@to_status_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Edit status in en_GB
    When I send a PATCH request to "/api/v1/en_GB/products/attributes" with body:
      """
       {
          "data": [
          {
            "id": "@edit_product@",
            "payload": [
              {
                "id": "@esa_status_id@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": "@from_status_id@"
                  }
                ]
              }
            ]
          }
        ]
      }
      """
    Then the response status code should be 204

  Scenario: Get status product status
    When I send a GET request to "api/v1/en_GB/products/@edit_product@"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | attributes.esa_status.en_GB | @from_status_id@      |
      | attributes.esa_status.pl_PL | @to_status_id@ |

  Scenario: Delete option (used in product)
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options/@select_option_1_id@"
    Then the response status code should be 409