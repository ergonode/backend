Feature: File attribute manipulation

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get image multimedia id
    When I send a GET request to "api/v1/en_GB/multimedia?columns=name&filter=name=product-1.jpg&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "multimedia_image_id"

  Scenario: Get document multimedia id
    When I send a GET request to "api/v1/en_GB/multimedia?columns=name&filter=name=document-1.txt&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "multimedia_document_id"

  Scenario: Create file attribute
    And I send a "POST" request to "/api/v1/en_GB/attributes" with body:
      """
      {
          "code": "FILE_@@random_code@@",
          "type": "FILE",
          "scope": "local",
          "groups": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "attribute_id"

  Scenario: Validate file attribute value (not exists image)
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate" with body:
      """
      {
        "value": ["not id"]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.value.element-0[0]" should contain "Multimedia not id not exists."

  Scenario: Validate file attribute value (not image)
    When I send a POST request to "api/v1/en_GB/attribute/@attribute_id@/validate" with body:
      """
      {
        "value":  ["@multimedia_document_id@", "@multimedia_image_id@"]
      }
      """
    Then the response status code should be 200

  Scenario: Update file attribute
    And I send a "PUT" request to "/api/v1/en_GB/attributes/@attribute_id@" with body:
      """
      {
        "type": "FILE",
        "scope": "local",
        "groups": []
      }
      """
    Then the response status code should be 204

  Scenario: Get file attribute
    And I send a "GET" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 200
    And the JSON nodes should be equal to:
      | id    | @attribute_id@ |
      | type  | FILE           |
      | scope | local          |

  Scenario: Delete file attribute
    And I send a "DELETE" request to "/api/v1/en_GB/attributes/@attribute_id@"
    Then the response status code should be 204
