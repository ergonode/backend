Feature: Product edit feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create text attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "scope": "local",
        "type": "TEXT",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_text_attribute"

  Scenario: Create textarea attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "TEXT_AREA_@@random_code@@",
        "scope": "local",
        "type": "TEXT_AREA",
        "groups": [],
        "parameters":
          {
          "richEdit": true
          }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_textarea_attribute"

  Scenario: Create select attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "SELECT_@@random_code@@",
        "scope": "local",
        "type": "SELECT",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_select_attribute"

  Scenario: Create option 1 for select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options" with body:
      """
      {
        "code": "key_a",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_option_1"

  Scenario: Create option 2 for select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options" with body:
      """
      {
        "code": "key_b",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_option_2"

  Scenario: Create option 3 for select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options" with body:
      """
      {
        "code": "key_c",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_option_c"

  Scenario: Create option 4 for select attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options" with body:
      """
      {
        "code": "key_d",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "select_option_4"

  Scenario: Create multi select attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "MULTI_SELECT_@@random_code@@",
        "type": "MULTI_SELECT",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_multi_select_attribute"

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

  Scenario: Create option 1 for multiselect attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_multi_select_attribute@/options" with body:
      """
      {
        "code": "key_aa",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_1"

  Scenario: Create option 2 for multiselect attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_multi_select_attribute@/options" with body:
      """
      {
        "code": "key_bb",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_2"

  Scenario: Create option 3 for multiselect attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_multi_select_attribute@/options" with body:
      """
      {
        "code": "key_cc",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_3"

  Scenario: Create option 4 for multiselect attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes/@product_edit_multi_select_attribute@/options" with body:
      """
      {
        "code": "key_dd",
        "label":  {}
      }
      """
    Then the response status code should be 201
    And store response param "id" as "multi_select_option_4"

  Scenario: Create unit attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "UNIT_@@random_code@@",
        "type": "UNIT",
        "scope": "local",
        "groups": [],
        "parameters": {
          "unit":"@unit_id@"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_unit_attribute"

  Scenario: Create price attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "PRICE_@@random_code@@",
        "type": "PRICE",
        "scope": "local",
        "groups": [],
        "parameters": {
          "currency":"EUR"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_price_attribute"

  Scenario: Create date attribute
    When I send a POST request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "DATE_@@random_code@@",
        "type": "DATE",
        "scope": "local",
        "groups": [],
        "parameters": {
          "format":"yyyy-MM-dd"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_date_attribute"

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
    And store response param "collection[0].source" as "source_status_id"
    And store response param "collection[0].destination" as "destination_status_id"

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
                    "value": "@select_option_1@"
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
                "id": "@product_edit_multi_select_attribute@",
                "values" : [
                  {
                    "language": "en_GB",
                    "value": ["@multi_select_option_1@", "@multi_select_option_4@"]
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
                    "value": "@destination_status_id@"
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
                    "value": "@source_status_id@"
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
      | attributes.esa_status.en_GB | @source_status_id@      |
      | attributes.esa_status.pl_PL | @destination_status_id@ |

  Scenario: Delete option (used in product)
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@product_edit_select_attribute@/options/@select_option_1@"
    Then the response status code should be 409

  Scenario: Request product
    When I send a GET request to "api/v1/en_GB/products/@edit_product@"
    Then the response status code should be 200
#    And print last JSON response
#    And the JSON nodes should be equal to:
#    | attributes.text_tutaj_uuid | text attribute value |
#    | attributes.text_area_@product_edit_textarea_attribute@ | textarea attribute value |
#
#    And the response body matches:
#    """
#      /"value": "text attribute value"/
#    """
#    And the response body matches:
#    """
#      /"value": "textarea attribute value"/
#    """
#    And the response body matches:
#    """
#      /"value": "key_a"/
#    """
#    And the response body matches:
#    """
#      /"value": \[\n[ ]*"key_aa",\n[ ]*"key_dd"\n[ ]*\]/
#    """
#    And the response body matches:
#    """
#      /"categories": \[\]/
#    """
#    And the response body matches:
#    """
#      /"value": "9999.99"/
#    """
#    And the response body matches:
#    """
#      /"value": "102030"/
#    """
#    And the response body matches:
#    """
#      /"value": "2019-12-30"/
#    """

