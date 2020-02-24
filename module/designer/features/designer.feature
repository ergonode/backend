Feature: Designer module

  Scenario: Create text attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@random_code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": [],
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
          "label": {"PL": "Atrybut zdjeciowy", "EN": "Image attribute"},
          "groups": [],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "template_image_attribute"

  Scenario: Multimedia upload image
    Given current authentication token
    Given I attach "module/designer/features/image/test.jpg" to the request as "upload"
    When I request "/api/v1/multimedia/upload" using HTTP POST
    Then created response is received
    And remember response param "id" as "multimedia_id"

  Scenario: Create template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "defaultText": "@template_text_attribute@",
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
    When I request "/api/v1/EN/templates" using HTTP POST
    Then created response is received
    And remember response param "id" as "template"


  Scenario: Create template (wrong default text attribute)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "defaultText": "@template_image_attribute@",
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
    Then validation error response is received

  Scenario: Create template (wrong default image attribute)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
        "defaultImage": "@template_text_attribute@",
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
    Then validation error response is received

  Scenario: Create template (not authorized)
    When I request "/api/v1/EN/templates" using HTTP POST
    Then unauthorized response is received

  Scenario: Create template (wrong image)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "test",
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
    Then validation error response is received

  Scenario: Create template (wrong position)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
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
    When I request "/api/v1/EN/templates" using HTTP POST
    Then validation error response is received

  Scenario: Create template (wrong size)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
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
    When I request "/api/v1/EN/templates" using HTTP POST
    Then validation error response is received

  Scenario: Create template (wrong attribute_id)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
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
    When I request "/api/v1/EN/templates" using HTTP POST
    Then validation error response is received

  Scenario: Create template (wrong required)
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "image": "@multimedia_id@",
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
    When I request "/api/v1/EN/templates" using HTTP POST
    Then validation error response is received

  Scenario: Update template
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then empty response is received

  Scenario: Update template (not authorized)
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update template (not found)
    Given current authentication token
    When I request "/api/v1/EN/templates/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update template (wrong image)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then validation error response is received

  Scenario: Update template (wrong position)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then validation error response is received

  Scenario: Update template (wrong size)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then validation error response is received

  Scenario: Update template (wrong attribute_id)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then validation error response is received

  Scenario: Update template (wrong required)
    Given current authentication token
    Given the request body is:
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
    When I request "/api/v1/EN/templates/@template@" using HTTP PUT
    Then validation error response is received

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

  Scenario: Get templates
    Given current authentication token
    When I request "/api/v1/EN/templates" using HTTP GET
    Then grid response is received

  Scenario: Get templates (not authorized)
    When I request "/api/v1/EN/templates" using HTTP GET
    Then unauthorized response is received

  Scenario: Get templates (order by id)
    Given current authentication token
    When I request "/api/v1/EN/templates?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get templates (order by name)
    Given current authentication token
    When I request "/api/v1/EN/templates?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get templates (order by image_id)
    Given current authentication token
    When I request "/api/v1/EN/templates?field=image_id" using HTTP GET
    Then grid response is received

  Scenario: Get templates (order by group_id)
    Given current authentication token
    When I request "/api/v1/EN/templates?field=group_id" using HTTP GET
    Then grid response is received

  Scenario: Get templates (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/templates?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get templates (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/templates?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get templates (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/templates?limit=25&offset=0&filter=id%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get templates (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/templates?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get templates (filter by image_id)
    Given current authentication token
    When I request "/api/v1/EN/templates?limit=25&offset=0&filter=image_id%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get templates (filter by group_id)
    Given current authentication token
    When I request "/api/v1/EN/templates?limit=25&offset=0&filter=group_id%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2" using HTTP GET
    Then grid response is received

  Scenario: Get template groups
    Given current authentication token
    When I request "/api/v1/EN/templates/groups" using HTTP GET
    Then the response code is 200

  Scenario: Get template groups (not authorized)
    When I request "/api/v1/EN/templates/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Get templates groups (order by id)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (order by name)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?field=name" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (order by custom)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?field=custom" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?field=name&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?field=name&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?limit=25&offset=0&filter=id%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (filter by name)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?limit=25&offset=0&filter=name%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get templates groups (filter by custom)
    Given current authentication token
    When I request "/api/v1/EN/templates/groups?limit=25&offset=0&filter=custom%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get template types
    Given current authentication token
    When I request "/api/v1/EN/templates/types" using HTTP GET
    Then the response code is 200

  Scenario: Get template types (not authorized)
    When I request "/api/v1/EN/templates/types" using HTTP GET
    Then unauthorized response is received

  Scenario: Get templates types (order by type)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=type" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order by variant)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=variant" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order by label)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=label" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order by min_width)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=min_width" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order by min_height)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=min_height" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order by max_width)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=max_width" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order by max_height)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=max_height" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=type&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?field=type&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=id%3D1" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by type)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=type%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by variant)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=variant%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by label)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=label%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by min_width)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=min_width%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by max_width)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=max_width%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by max_height)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=max_height%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2" using HTTP GET
    Then grid response is received

  Scenario: Get templates types (filter by min_height)
    Given current authentication token
    When I request "/api/v1/EN/templates/types?limit=25&offset=0&filter=min_height%3D4fbba5a0-61c7-5dc8-ba1b-3314f398bfa2" using HTTP GET
    Then grid response is received

  Scenario: Delete template
    Given current authentication token
    When I request "/api/v1/EN/templates/@template@" using HTTP DELETE
    Then empty response is received
