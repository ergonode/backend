Feature: Multimedia relations
  In order to mange Multimedia
  I need to be able to create and retrieve through the API.

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"

  Scenario: Upload new multimedia file
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value                      |
      | upload | @multimedia-test-image.png |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_id"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "elements": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "template_id"

  Scenario: Create image attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "MULTIMEDIA_RELATION_@@random_code@@",
          "type": "IMAGE",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
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

  Scenario: Edit product image value in "en_GB" language
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
                      "value": "@multimedia_id@"
                    }
                  ]
                }
              ]
            }
          ]
        }
      """
    Then the response status code should be 200

  Scenario: Get multimedia relation
    When I send a GET request to "/api/v1/en_GB/multimedia/@multimedia_id@/relation"
    Then the response status code should be 200
    And the JSON node "[0].name" should exist
    And the JSON node "[0].type" should exist

  Scenario: Delete multimedia
    And I send a "DELETE" request to "/api/v1/en_GB/multimedia/@multimedia_id@"
    Then the response status code should be 409
