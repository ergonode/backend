Feature: Export Profile Shopware 6 API

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get configuration with Shopware 6 API
    When I send a GET request to "/api/v1/en/channels/shopware-6-api/configuration"
    Then the response status code should be 200

  Scenario: Create numeric attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "NUMERIC_ATTRIBUTE",
          "type": "NUMERIC",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_numeric_id"

  Scenario: Create price attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "PRICE_ATTRIBUTE",
        "type": "PRICE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "currency": "EUR"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_price_id"

  Scenario: Create text attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "TEXT_ATTRIBUTE",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_text_id"

  Scenario: Create date attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "DATE_ATTRIBUTE",
        "type": "DATE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "format": "yyyy-MM-dd"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_date_id"

  Scenario: Create image attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "IMAGE_ATTRIBUTE",
          "type": "IMAGE",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_image_id"

  Scenario: Create select attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "SELECT_ATTRIBUTE",
          "type": "SELECT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_select_id"

  Scenario: Create option for attribute
    And I send a "POST" request to "/api/v1/en/attributes/@attribute_select_id@/options" with body:
      """
      {
        "code": "s_option_1",
        "label":  {
          "pl": "Option pl 1",
          "en": "Option en 1"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_select_option_id1"

  Scenario: Create option for attribute
    And I send a "POST" request to "/api/v1/en/attributes/@attribute_select_id@/options" with body:
      """
      {
        "code": "s_option_2",
        "label":  {
          "pl": "Option pl 2",
          "en": "Option en 2"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_select_option_id2"

  Scenario: Create multi select attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
          "code": "MULTI_SELECT_ATTRIBUTE",
          "type": "MULTI_SELECT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_multi_select_id"

  Scenario: Create option for attribute
    And I send a "POST" request to "/api/v1/en/attributes/@attribute_multi_select_id@/options" with body:
      """
      {
        "code": "m_option_1",
        "label":  {
          "pl": "Multi SELECT Option pl 1",
          "en": "MULTI SELECT Option en 1"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_multi_select_option_id1"

  Scenario: Create option for attribute
    And I send a "POST" request to "/api/v1/en/attributes/@attribute_multi_select_id@/options" with body:
      """
      {
        "code": "m_option_2",
        "label":  {
          "pl": "MULTI SELECT Option pl 2",
          "en": "MULTI SELECT Option en 2"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_multi_select_option_id2"

  Scenario: Post Create Export profile to Shopware 6 API
    When I send a POST request to "/api/v1/en/channels" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://shopware.local",
          "client_id": "SWIACFO0NML3TW9UBTHNQZK2NG",
          "client_key": "SE1wV2t6WmNuM0hCTXNkaEw4Q1ZiN1A3OThvUHVWNGg0dkpDcDY",
          "default_language": "en",
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price" : "@attribute_price_id@",
          "attribute_product_tax" : "@attribute_numeric_id@"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

# # # # # # # # # # # # # # # # # # # #
#  PRODUCTS

  Scenario: Create template
    When I send a POST request to "/api/v1/en/templates" with body:
      """
      {
        "name": "TEMPLATE",
        "elements":[
            {
              "position": {
                "x": 0,
                "y": 0
              },
              "size": {
                "width": 4,
                "height": 1
              },
              "properties": {
                "attribute_id": "@attribute_text_id@",
                "required": false
              },
              "type": "TEXT"
            },
          {
            "position": {
              "x": 0,
              "y": 1
              },
              "size": {
                "width": 1,
                "height": 1
              },
              "properties": {
                "attribute_id": "@attribute_date_id@",
                "required": false
              },
              "type": "DATE"
          },
          {
            "position": {
              "x": 2,
              "y": 1
            },
            "size": {
              "width": 1,
              "height": 1
            },
            "properties": {
              "attribute_id": "@attribute_multi_select_id@",
              "required": false
            },
            "type": "MULTI_SELECT"
          },
          {
            "position": {
              "x": 3,
              "y": 1
            },
            "size": {
              "width": 1,
              "height": 1
            },
            "properties": {
              "attribute_id": "@attribute_numeric_id@",
              "required": false
            },
            "type": "NUMERIC"
          },
          {
            "position": {
              "x": 0,
              "y": 2
            },
            "size": {
              "width": 4,
              "height": 4
            },
            "properties": {
              "attribute_id": "@attribute_image_id@",
              "required": false
            },
            "type": "IMAGE"
          },
          {
            "position": {
              "x": 0,
              "y": 6
            },
            "size": {
              "width": 1,
              "height": 1
            },
            "properties": {
              "attribute_id": "@attribute_price_id@",
              "required": false
            },
            "type": "PRICE"
          },
          {
            "position": {
              "x": 1,
              "y": 1
            },
            "size": {
              "width": 1,
              "height": 1
            },
            "properties": {
              "attribute_id": "@attribute_select_id@",
              "required": false
            },
            "type": "SELECT"
          }
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "template_id"

  Scenario: Create product
    When I send a POST request to "/api/v1/en/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@template_id@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id"

#  Scenario: Run export
#    When I send a POST request to "/api/v1/en/channels/@channel_id@/exports"
#    And print last response
#    Then the response status code should be 201
#    And store response param "id" as "export_id"

