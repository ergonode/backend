Feature: Export Profile Shopware 6 API

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get configuration with Shopware 6 API
    When I send a GET request to "/api/v1/en_GB/channels/shopware-6-api/configuration"
    Then the response status code should be 200

  Scenario: Create numeric attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "shopware_6_NUMERIC_@@random_code@@",
          "type": "NUMERIC",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_numeric_id"

  Scenario: Create gross price attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "shopware_6_PRICE_gross_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "currency": "PLN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_price_gross_id"

  Scenario: Create net price attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "shopware_6_PRICE_net_@@random_code@@",
        "type": "PRICE",
        "groups": [],
        "scope": "local",
        "parameters":
        {
          "currency": "PLN"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_price_net_id"

  Scenario: Create text attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "shopware_6_TEXT_@@random_code@@",
          "type": "TEXT",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_text_id"

  Scenario: Create textarea attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "shopware_6_TEXT_AREA_@@random_code@@",
        "type": "TEXT_AREA",
        "groups": [],
        "scope": "local",
        "parameters":
        {
        "richEdit": true
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_text_area_id"

  Scenario: Create gallery attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "shopware_6_GALLERY_@@random_code@@",
          "type": "GALLERY",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_gallery_id"

  Scenario: Create category tree
    When I send a POST request to "/api/v1/en_GB/trees" with body:
      """
      {
        "code": "TREE_@@random_code@@",
        "name": {
          "de_DE": "Test tree1 de",
          "en_GB": "Test tree1 en",
          "pl_PL": "Test tree1 PL"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "category_tree"

  Scenario: Create select attribute
    When I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
        "code": "SELECT_@@random_code@@",
        "type": "SELECT",
        "scope": "local",
        "groups": [],
        "label":
        {
          "pl_PL": "Typ",
          "en_GB": "Type"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_select_id"

  Scenario: Post Create Channel to Shopware 6 API
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en_GB",
          "languages": ["pl_PL", "en_GB"],
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price_gross" : "@attribute_price_gross_id@",
          "attribute_product_price_net" : "@attribute_price_net_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "category_tree" : "@category_tree@"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

  Scenario: Post Create Channel to Shopware 6 API (wrong languages not active)
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en_GB",
          "languages": ["es_ES", "en_GB"],
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price_gross" : "@attribute_price_gross_id@",
          "attribute_product_price_net" : "@attribute_price_net_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "attribute_product_gallery" : "@attribute_gallery_id@",
          "category_tree" : "@category_tree@"
        }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.languages[0] | is not valid |

  Scenario: Post Create Channel to Shopware 6 API (wrong languages invalid)
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en_GB",
          "languages": ["ps_AR", "en_GB"],
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price_gross" : "@attribute_price_gross_id@",
          "attribute_product_price_net" : "@attribute_price_net_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "category_tree" : "@category_tree@"
        }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.languages[0] | is not valid |


  Scenario: Post Create Channel to Shopware 6 API (wrong default language not active)
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "es_ES",
          "languages": ["pl_PL", "en_GB"],
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price_gross" : "@attribute_price_gross_id@",
          "attribute_product_price_net" : "@attribute_price_net_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "category_tree" : "@category_tree@"
        }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.default_language[0] | This value is not valid |

  Scenario: Post Create Channel to Shopware 6 API (wrong  default language invalid)
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "ps_AR",
          "languages": ["pl_PL", "en_GB"],
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price_gross" : "@attribute_price_gross_id@",
          "attribute_product_price_net" : "@attribute_price_net_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "category_tree" : "@category_tree@"
        }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.default_language[0] | This value is not valid |

  Scenario: Update shopware 6 channel
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api - TEST",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en_GB",
          "languages": ["en_GB"],
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price_gross" : "@attribute_price_gross_id@",
          "attribute_product_price_net" : "@attribute_price_net_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "attribute_product_description" : "@attribute_text_area_id@",
          "category_tree" : "@category_tree@",
          "property_group": [
            {
              "id": "@attribute_select_id@"
            }
          ],
          "custom_field": [
            {
              "id": "@attribute_text_id@"
            }
          ]

        }
      """
    Then the response status code should be 204
