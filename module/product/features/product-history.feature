Feature: Product history feature

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@",
        "elements": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_edit_template"

  Scenario: Create product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_edit_template@",
        "categoryIds": []
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product"

  Scenario: Get products history (order by recorded_at)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?field=recorded_at"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].recorded_at" should exist

  Scenario: Get products history (order by event)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?field=event"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].event:en_GB" should exist

  Scenario: Get products history (filter by time)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get products history (filter by null time)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at="
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get products history (filter by time lower or equal 2000-01-01)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at<=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get products history (filter by time lower or equal 2000-01-01)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at>=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products history (filter by time greater or equal 2050-01-01)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at>=2050-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get products history (filter by time greater or equal 2050-01-01)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at<=2050-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products history (filter by time in range 2000-01-01 -2050-01-01)
    When I send a GET request to "/api/v1/en_GB/products/@product@/history?limit=25&offset=0&filter=recorded_at<=2050-01-01;recorded_at>=2000-01-01"
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
