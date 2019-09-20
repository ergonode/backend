Feature: Designer module

  Scenario: Get attribute groups dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then the response code is 200
    And remember first attribute group as "attribute_group"

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "template_text_attribute"

  Scenario: Create image attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "IMAGE_@@random_code@@",
          "type": "IMAGE",
          "groups": ["@attribute_group@"],
          "parameters": {"formats": ["jpg"]}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "template_image_attribute"

  Scenario: Create template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@template_image_attribute@",
        "elements": [
          {
            "position": {"x": 0, "y": 0},
            "size": {"width": 2, "height": 1},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "@template_text_attribute@",
              "required": true
            }
          }
        ]
      }
      """
    When I request "/api/v1/EN/templates" using HTTP POST
    Then created response is received
    And remember response param "id" as "template"

  Scenario: Create template (not authorized)
    When I request "/api/v1/EN/templates" using HTTP POST
    Then unauthorized response is received

  Scenario: Update template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@template_image_attribute@",
        "elements": [
          {
            "position": {"x": 10, "y": 10},
            "size": {"width": 2, "height": 2},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "@template_text_attribute@",
              "required": true
            }
          }
        ]
      }
      """
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then empty response is received

  Scenario: Update template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update template (not found)
    Given current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Delete template
    Given current authentication token
    When I request "/api/v1/EN/templates/@template@" using HTTP DELETE
    Then empty response is received

  Scenario: Delete template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete template (not found)
    Given current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Get template
    Given current authentication token
    When I request "/api/v1/EN/templates/@template@" using HTTP GET
    Then the response code is 200

  Scenario: Get template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get template (not found)
    Given current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get template groups
    Given current authentication token
    When I request "/api/v1/EN/templates/groups" using HTTP GET
    Then the response code is 200

  Scenario: Get template groups (not authorized)
    When I request "/api/v1/EN/templates/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Get template types
    Given current authentication token
    When I request "/api/v1/EN/templates/types" using HTTP GET
    Then the response code is 200

  Scenario: Get template types (not authorized)
    When I request "/api/v1/EN/templates/types" using HTTP GET
    Then unauthorized response is received

  Scenario: Get templates
    Given current authentication token
    When I request "/api/v1/EN/templates" using HTTP GET
    Then the response code is 200

  Scenario: Get templates (not authorized)
    When I request "/api/v1/EN/templates" using HTTP GET
    Then unauthorized response is received

  # TODO Check template grid
  # TODO Check template group grid
  # TODO Check template type grid
  # TODO Check create template action with all incorrect possibilities
  # TODO Check update template action with all incorrect possibilities
