Feature: Image attribute manipulation

  Scenario: Create image attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "IMAGE_@@random_code@@",
          "type": "IMAGE",
          "groups": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "image_attribute"

  Scenario: Update image attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "IMAGE",
          "groups": []
      }
      """
    When I request "/api/v1/EN/attributes/@image_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete image attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@image_attribute@" using HTTP DELETE
    Then empty response is received

