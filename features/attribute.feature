Feature: Attribute module

  Scenario: Get attribute types dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/attributes/types" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute types dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/attributes/types" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute groups dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then the response code is 200
    And remember first attribute group as "attribute_group"

  Scenario: Get attribute groups dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Delete attribute (not found)
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Create attribute (not authorized)
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then unauthorized response is received

  Scenario: Create text attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "TEXT_@@code@@",
          "type": "TEXT",
          "label": {"PL": "Atrybut tekstowy", "EN": "Text attribute"},
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "text_attribute"

  Scenario: Update text attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Given the request body is:
      """
      {
          "type": "TEXT",
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    Then the response code is 200

  Scenario: Update text attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update text attribute (not found)
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute (not found)
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Delete text attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete text attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create textarea attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "TEXTAREA_@@code@@",
          "type": "TEXTAREA",
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "textarea_attribute"

  Scenario: Update textarea attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "TEXTAREA",
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes/@textarea_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete textarea attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@textarea_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create select attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "SELECT_@@code@@",
          "type": "SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "select_attribute"

  Scenario: Update select attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete select attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create multiselect attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "MULTISELECT_@@code@@",
          "type": "MULTI_SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "multiselect_attribute"

  Scenario: Update multiselect attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "MULTI_SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete multiselect attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create image attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "IMAGE_@@code@@",
          "type": "IMAGE",
          "groups": ["@attribute_group@"],
          "parameters": {"formats": ["jpg"]}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "image_attribute"

  Scenario: Update image attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "IMAGE",
          "groups": ["@attribute_group@"],
          "parameters": {"formats": ["jpg"]}
      }
      """
    When I request "/api/v1/EN/attributes/@image_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete image attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@image_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create date attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "DATE_@@code@@",
          "type": "DATE",
          "groups": ["@attribute_group@"],
          "parameters": {"format": "YYYY-MM-DD"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "date_attribute"

  Scenario: Update date attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "DATE",
          "groups": ["@attribute_group@"],
          "parameters": {"format": "YYYY-MM-DD"}
      }
      """
    When I request "/api/v1/EN/attributes/@date_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete date attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@date_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create price attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "PRICE_@@code@@",
          "type": "PRICE",
          "groups": ["@attribute_group@"],
          "parameters": {"currency": "PLN"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "price_attribute"

  Scenario: Update price attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "PRICE",
          "groups": ["@attribute_group@"],
          "parameters": {"currency": "PLN"}
      }
      """
    When I request "/api/v1/EN/attributes/@price_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete price attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@price_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create unit attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "code": "UNIT_@@code@@",
          "type": "UNIT",
          "groups": ["@attribute_group@"],
          "parameters": {"unit": "M"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "unit_attribute"

  Scenario: Update unit attribute
    Given Current authentication token
    Given the request body is:
      """
      {
          "type": "UNIT",
          "groups": ["@attribute_group@"],
          "parameters": {"unit": "M"}
      }
      """
    When I request "/api/v1/EN/attributes/@unit_attribute@" using HTTP PUT
    Then the response code is 200

  Scenario: Delete unit attribute
    Given Current authentication token
    When I request "/api/v1/EN/attributes/@unit_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Get attributes (order by code)
    Given Current authentication token
    When I request "/api/v1/EN/attributes?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by label)
    Given Current authentication token
    When I request "/api/v1/EN/attributes?field=label" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by type)
    Given Current authentication token
    When I request "/api/v1/EN/attributes?field=type" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by multilingual)
    Given Current authentication token
    When I request "/api/v1/EN/attributes?field=multilingual" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (not authorized)
    When I request "/api/v1/EN/attributes" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute image formats dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/image_format" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute image formats dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/image_format" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute units dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/units" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute units dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/units" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute currencies dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/currencies" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute currencies dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/currencies" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute date formats dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/date_format" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute currencies date formats (not authorized)
    When I request "/api/v1/EN/dictionary/date_format" using HTTP GET
    Then unauthorized response is received

  # TODO Check attributes with all filters
  # TODO Check create attribute action with all incorrect possibilities
  # TODO Check update attribute action with all incorrect possibilities
