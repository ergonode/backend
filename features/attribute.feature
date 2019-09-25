Feature: Attribute module

  Scenario: Get attribute types dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/attributes/types" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute types dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/attributes/types" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute groups dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then the response code is 200
    And remember first attribute group as "attribute_group"

  Scenario: Get attribute groups dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attributes (order by id)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=id" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=elements_count" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order ASC)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label&order=ASC" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order DESC)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label&order=DESC" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=label%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by id)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=id%3DEN" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by elements_count)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=elements_count%3D1" using HTTP GET
    Then grid response is received

  Scenario: Delete attribute (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP DELETE
    Then not found response is received

  Scenario: Create attribute (not authorized)
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then unauthorized response is received

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
    And remember response param "id" as "text_attribute"

  Scenario: Update text attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Given the request body is:
      """
      {
          "type": "TEXT",
          "groups": ["@attribute_group@"],
          "label": {"PL": "PL", "EN": "EN"},
          "placeholder": {"PL": "PL", "EN": "EN"},
          "hint": {"PL": "PL", "EN": "EN"},
          "parameters": []
      }
      """
    Then empty response is received

  Scenario: Update text attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update text attribute (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Get attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute (not found)
    Given current authentication token
    When I request "/api/v1/EN/attributes/@@static_uuid@@" using HTTP GET
    Then not found response is received

  Scenario: Delete text attribute (not authorized)
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP DELETE
    Then unauthorized response is received

  Scenario: Delete text attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@text_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create textarea attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "TEXTAREA_@@random_code@@",
          "type": "TEXTAREA",
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "textarea_attribute"

  Scenario: Update textarea attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "TEXTAREA",
          "groups": ["@attribute_group@"],
          "parameters": []
      }
      """
    When I request "/api/v1/EN/attributes/@textarea_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete textarea attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@textarea_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create select attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "SELECT_@@random_code@@",
          "type": "SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "select_attribute"

  Scenario: Update select attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete select attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@select_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create multiselect attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "MULTISELECT_@@random_code@@",
          "type": "MULTI_SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "multiselect_attribute"

  Scenario: Update multiselect attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "MULTI_SELECT",
          "groups": ["@attribute_group@"]
      }
      """
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete multiselect attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@multiselect_attribute@" using HTTP DELETE
    Then empty response is received

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
    And remember response param "id" as "image_attribute"

  Scenario: Update image attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "IMAGE",
          "groups": ["@attribute_group@"],
          "parameters": {"formats": ["jpg"]}
      }
      """
    When I request "/api/v1/EN/attributes/@image_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete image attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@image_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create date attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "DATE_@@random_code@@",
          "type": "DATE",
          "groups": ["@attribute_group@"],
          "parameters": {"format": "YYYY-MM-DD"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "date_attribute"

  Scenario: Update date attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "DATE",
          "groups": ["@attribute_group@"],
          "parameters": {"format": "YYYY-MM-DD"}
      }
      """
    When I request "/api/v1/EN/attributes/@date_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete date attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@date_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create price attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "PRICE_@@random_code@@",
          "type": "PRICE",
          "groups": ["@attribute_group@"],
          "parameters": {"currency": "PLN"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "price_attribute"

  Scenario: Update price attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "PRICE",
          "groups": ["@attribute_group@"],
          "parameters": {"currency": "PLN"}
      }
      """
    When I request "/api/v1/EN/attributes/@price_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete price attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@price_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Create unit attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "code": "UNIT_@@random_code@@",
          "type": "UNIT",
          "groups": ["@attribute_group@"],
          "parameters": {"unit": "M"}
      }
      """
    When I request "/api/v1/EN/attributes" using HTTP POST
    Then created response is received
    And remember response param "id" as "unit_attribute"

  Scenario: Update unit attribute
    Given current authentication token
    Given the request body is:
      """
      {
          "type": "UNIT",
          "groups": ["@attribute_group@"],
          "parameters": {"unit": "M"}
      }
      """
    When I request "/api/v1/EN/attributes/@unit_attribute@" using HTTP PUT
    Then empty response is received

  Scenario: Delete unit attribute
    Given current authentication token
    When I request "/api/v1/EN/attributes/@unit_attribute@" using HTTP DELETE
    Then empty response is received

  Scenario: Get attributes (order by code)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=code" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=label" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by type)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=type" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (order by multilingual)
    Given current authentication token
    When I request "/api/v1/EN/attributes?field=multilingual" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by index)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=index%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by code)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=code%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by label)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=label%3Dasd" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by type)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=type%3DTEXT" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (filter by groups)
    Given current authentication token
    When I request "/api/v1/EN/attributes?limit=25&offset=0&filter=groups%3Dd653cce6-66fb-4772-800b-281af35fc5bc" using HTTP GET
    Then grid response is received

  Scenario: Get attributes (not authorized)
    When I request "/api/v1/EN/attributes" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute image formats dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/image_format" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute image formats dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/image_format" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute units dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/units" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute units dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/units" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute currencies dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/currencies" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute currencies dictionary (not authorized)
    When I request "/api/v1/EN/dictionary/currencies" using HTTP GET
    Then unauthorized response is received

  Scenario: Get attribute date formats dictionary
    Given current authentication token
    When I request "/api/v1/EN/dictionary/date_format" using HTTP GET
    Then the response code is 200

  Scenario: Get attribute currencies date formats (not authorized)
    When I request "/api/v1/EN/dictionary/date_format" using HTTP GET
    Then unauthorized response is received
