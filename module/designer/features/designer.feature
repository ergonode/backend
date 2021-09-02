Feature: Designer module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get local text attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=text_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_text_attribute"

  Scenario: Get local image attribute id
    When I send a GET request to "/api/v1/en_GB/attributes?filter=code=image_attribute_local&view=list"
    Then the response status code should be 200
    And store response param "collection[0].id" as "template_image_attribute"

  Scenario: Multimedia upload image
    When I send a POST request to "/api/v1/multimedia/upload" with params:
      | key    | value           |
      | upload | @image/test.jpg |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "multimedia_id"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "code": "code_@@random_md5@@",
        "image": "@multimedia_id@",
        "defaultLabel": "@template_text_attribute@",
        "defaultImage": "@template_image_attribute@",
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
    Then the response status code should be 201
    And store response param "id" as "template"

  Scenario: Create template (code not exists)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.code[0]" should contain "Template code is required"

  Scenario: Create template (to long code)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "code": "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii",
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.code[0]" should contain "Template code is too long. It should contain 128 characters or less."

  Scenario: Create template (unique code)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "code": "template",
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.code[0]" should contain "Template code is not unique."

  Scenario: Create template (invalid code)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "code": "template_!@#",
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.code[0]" should contain "Template code can have only letters, digits or underscore symbol."

  Scenario: Create template (wrong default label attribute)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "defaultLabel": "@template_image_attribute@"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.defaultLabel" should exist

  Scenario: Create template (wrong default image attribute)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "code": "code_@@random_md5@@",
        "defaultImage": "@template_text_attribute@"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.defaultImage" should exist

  Scenario: Create template (wrong image)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "test"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.image" should exist

  Scenario: Create template (wrong position)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "code": "code_@@random_md5@@",
        "elements": [
          {
            "position": {"x": "test", "y": 0},
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
    Then the response status code should be 400
    And the JSON node "errors.elements.element-0.position.x" should exist

  Scenario: Create template (wrong size)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "code": "code_@@random_md5@@",
        "elements": [
          {
            "position": {"x": 0, "y": 0},
            "size": {"width": "test", "height": 1},
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
    Then the response status code should be 400
    And the JSON node "errors.elements.element-0.size.width" should exist

  Scenario: Create template (wrong attribute_id)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "code": "code_@@random_md5@@",
        "elements": [
          {
            "position": {"x": 0, "y": 0},
            "size": {"width": "test", "height": 1},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "test",
              "required": true
            }
          }
        ]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.elements.element-0.properties.attribute_id" should exist

  Scenario: Create template (wrong element required)
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "code": "code_@@random_md5@@",
        "elements": [
          {
            "position": {"x": 0, "y": 0},
            "size": {"width": 2, "height": 1},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "@template_text_attribute@",
              "required": "test"
            }
          }
        ]
      }
      """
    Then the response status code should be 400
    And the JSON node "errors.elements.element-0.properties.required" should exist

  Scenario: Update template
    When I send a PUT request to "/api/v1/en_GB/templates/@template@" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
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
    Then the response status code should be 204

  Scenario: Update template (not found)
    When I send a PUT request to "/api/v1/en_GB/templates/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update template (wrong image)
    When I send a PUT request to "/api/v1/en_GB/templates/@template@" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "test",
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
    Then the response status code should be 400

  Scenario: Update template (wrong position)
    When I send a PUT request to "/api/v1/en_GB/templates/@template@" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "elements": [
          {
            "position": {"x": "test", "y": 10},
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
    Then the response status code should be 400

  Scenario: Update template (wrong size)
    When I send a PUT request to "/api/v1/en_GB/templates/@template@" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "elements": [
          {
            "position": {"x": 10, "y": 10},
            "size": {"width": "test", "height": 2},
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
    Then the response status code should be 400

  Scenario: Update template (wrong attribute_id)
    When I send a PUT request to "/api/v1/en_GB/templates/@template@" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "elements": [
          {
            "position": {"x": 10, "y": 10},
            "size": {"width": 2, "height": 2},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "test",
              "required": true
            }
          }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Update template (wrong required)
    When I send a PUT request to "/api/v1/en_GB/templates/@template@" with body:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "elements": [
          {
            "position": {"x": 10, "y": 10},
            "size": {"width": "test", "height": 2},
            "variant": "attribute",
            "type": "text",
            "properties": {
              "attribute_id": "@template_text_attribute@",
              "required": "test"
            }
          }
        ]
      }
      """
    Then the response status code should be 400

  Scenario: Delete template (not found)
    When I send a DELETE request to "/api/v1/en_GB/templates/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get template
    When I send a GET request to "/api/v1/en_GB/templates/@template@"
    Then the response status code should be 200

  Scenario: Get template (not found)
    When I send a GET request to "/api/v1/en_GB/templates/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Get templates
    When I send a GET request to "/api/v1/en_GB/templates"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates (order ASC)
    When I send a GET request to "/api/v1/en_GB/templates?field=name&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates (order DESC)
    When I send a GET request to "/api/v1/en_GB/templates?field=name&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario Outline: Get templates (order by <field>)
    When I send a GET request to "/api/v1/en_GB/templates?field=<field>"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "collection[0].<field>" should exist
    Examples:
      | field    |
      | id       |
      | name     |
      | code     |
      | image_id |
      | group_id |

  Scenario Outline: Get templates (filter by <field>)
    When I send a GET request to "/api/v1/en_GB/templates?limit=25&offset=0&filter=<field>%3D<value>"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    Examples:
      | field    | value |
      | id       | 1     |
      | code     | code  |
      | name     | name  |
      | image_id | id    |
      | group_id | id    |

  Scenario: Get template groups
    When I send a GET request to "/api/v1/en_GB/templates/groups"
    Then the response status code should be 200

  Scenario: Get templates groups (order by id)
    When I send a GET request to "/api/v1/en_GB/templates/groups?field=id"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (order by name)
    When I send a GET request to "/api/v1/en_GB/templates/groups?field=name"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (order by custom)
    When I send a GET request to "/api/v1/en_GB/templates/groups?field=custom"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (order ASC)
    When I send a GET request to "/api/v1/en_GB/templates/groups?field=name&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (order DESC)
    When I send a GET request to "/api/v1/en_GB/templates/groups?field=name&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (filter by id)
    When I send a GET request to "/api/v1/en_GB/templates/groups?limit=25&offset=0&filter=id%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (filter by name)
    When I send a GET request to "/api/v1/en_GB/templates/groups?limit=25&offset=0&filter=name%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates groups (filter by custom)
    When I send a GET request to "/api/v1/en_GB/templates/groups?limit=25&offset=0&filter=custom%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get template types
    When I send a GET request to "/api/v1/en_GB/templates/types"
    Then the response status code should be 200

  Scenario: Get templates types (order by type)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=type"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order by variant)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=variant"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order by label)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=label"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order by min_width)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=min_width"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order by min_height)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=min_height"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order by max_width)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=max_width"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order by max_height)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=max_height"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order ASC)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=type&order=ASC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (order DESC)
    When I send a GET request to "/api/v1/en_GB/templates/types?field=type&order=DESC"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by id)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=id%3D1"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by type)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=type%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by variant)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=variant%3Dasd"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by label)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=label%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by min_width)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=min_width%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by max_width)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=max_width%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by max_height)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=max_height%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Get templates types (filter by min_height)
    When I send a GET request to "/api/v1/en_GB/templates/types?limit=25&offset=0&filter=min_height%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"

  Scenario: Delete template
    When I send a DELETE request to "/api/v1/en_GB/templates/@template@"
    Then the response status code should be 204
