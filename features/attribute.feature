Feature: Attribute module

  Scenario: Get attribute dictionary
    Given Current authentication token
    When I request "/api/v1/EN/dictionary/attributes/groups" using HTTP GET
    Then the response code is 200
    And remember first attribute group as "attribute_group"

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
