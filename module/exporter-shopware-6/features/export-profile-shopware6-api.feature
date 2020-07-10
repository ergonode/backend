Feature: Export Profile Shopware 6 API

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get configuration with Shopware 6 API
    When I send a GET request to "/api/v1/en/export-profile/shopware-6-api/configuration"
    Then the response status code should be 200

  Scenario: Create numeric attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
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

  Scenario: Create price attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
      """
      {
        "code": "shopware_6_PRICE_@@random_code@@",
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
    And store response param "id" as "attribute_price_id"

  Scenario: Create text attribute
    And I send a "POST" request to "/api/v1/en/attributes" with body:
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

  Scenario: Post Create Export profile to Shopware 6 API
    When I send a POST request to "/api/v1/en/export-profile" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en",
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price" : "@attribute_price_id@",
          "attribute_product_tax" : "@attribute_numeric_id@"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "export_profile_id"

  Scenario: Update Export Profile
    When I send a PUT request to "/api/v1/en/export-profile/@export_profile_id@" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api - TEST",
          "host": "http://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en",
          "attribute_product_name" : "@attribute_text_id@",
          "attribute_product_active" : "@attribute_numeric_id@",
          "attribute_product_stock" : "@attribute_numeric_id@",
          "attribute_product_price" : "@attribute_price_id@",
          "attribute_product_tax" : "@attribute_numeric_id@",
          "attribute_product_description" : "@attribute_text_id@",
          "property_group": [
            {
              "id": "@attribute_text_id@"
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

  Scenario: Create channel to Shopware6
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "name": "Shopware 6 Default",
        "export_profile_id": "@export_profile_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "channel"
