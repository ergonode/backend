Feature: Designer module

#  Scenario: Create template
#    Given Current authentication token
#    Given the request body is:
#      """
#      {
#        "name": "TEST @@random_code@@",
#        "image": "string",
#        "elements": [
#          {
#            "position": {
#              "x": 0,
#              "y": 0
#            },
#            "size": {
#              "width": 2,
#              "height": 1
#            },
#            "variant": "attribute",
#            "type": "text",
#            "properties": {
#              "attribute_id": "attribute_id",
#              "required": true
#            }
#          }
#        ]
#      }
#      """
#    When I request "/api/v1/EN/templates" using HTTP POST
#    Then created response is received
#    And remember response param "id" as "template"

  Scenario: Create template (not authorized)
    When I request "/api/v1/EN/templates" using HTTP POST
    Then unauthorized response is received

#  Scenario: Update template
#    Given Current authentication token
#    Given the request body is:
#      """
#      {
#        "image": "string",
#        "elements": [
#          {
#            "position": {
#              "x": 0,
#              "y": 0
#            },
#            "size": {
#              "width": 2,
#              "height": 1
#            },
#            "variant": "attribute",
#            "type": "text",
#            "properties": {
#              "attribute_id": "attribute_id",
#              "required": true
#            }
#          }
#        ]
#      }
#      """
#    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
#    Then the response code is 200

  Scenario: Update template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update template (not found)
    Given Current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP PUT
    Then not found response is received

#  Scenario: Delete template
#    Given Current authentication token
#    When I request "/api/v1/EN/templates/@template@" using HTTP DELETE
#    Then the response code is 200

  Scenario: Delete template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete template (not found)
    Given Current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

#  Scenario: Get template
#    Given Current authentication token
#    When I request "/api/v1/EN/templates/@template@" using HTTP GET
#    Then the response code is 200

  Scenario: Get template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get template (not found)
    Given Current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Get template groups
    Given Current authentication token
    When I request "/api/v1/EN/templates/groups" using HTTP GET
    Then the response code is 200

  Scenario: Get template groups (not authorized)
    When I request "/api/v1/EN/templates/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Get template types
    Given Current authentication token
    When I request "/api/v1/EN/templates/types" using HTTP GET
    Then the response code is 200

  Scenario: Get template types (not authorized)
    When I request "/api/v1/EN/templates/types" using HTTP GET
    Then unauthorized response is received

  Scenario: Get templates
    Given Current authentication token
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
