Feature: Product history feature

  Scenario: Create template
    Given current authentication token
    Given the request body is:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    When I request "/api/v1/EN/templates" using HTTP POST
    Then created response is received
    And remember response param "id" as "product_edit_template"

  Scenario: Create product
    Given current authentication token
    Given the request body is:
      """
      {
        "sku": "SKU_@@random_code@@",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    When I request "/api/v1/EN/products" using HTTP POST
    Then created response is received
    And remember response param "id" as "product"

  Scenario: Get products history (order by recorded_at)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?field=recorded_at" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"recorded_at"/
    """

  Scenario: Get products history (order by event)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?field=event" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"event:EN"/
    """


  Scenario: Get products history (filter by time)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get products history (filter by null time)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at=" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get products history (filter by time lower or equal 2000-01-01)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at<=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get products history (filter by time lower or equal 2000-01-01)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at>=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products history (filter by time greater or equal 2050-01-01)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at>=2050-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get products history (filter by time greater or equal 2050-01-01)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at<=2050-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products history (filter by time in range 2000-01-01 -2050-01-01)
    Given current authentication token
    When I request "/api/v1/EN/products/@product@/history?limit=25&offset=0&filter=recorded_at<=2050-01-01;recorded_at>=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get products history (not authorized)
    When I request "/api/v1/EN/products/@product@/history" using HTTP GET
    Then unauthorized response is received

