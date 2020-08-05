Feature: Product collection module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create first product collection type
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "name": {
          "de_DE": "Name de",
          "en_GB": "Name en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type_1"

  Scenario: Create second product collection type
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "name": {
          "de_DE": "Name de",
          "en_GB": "Name en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type_2"

  Scenario: Create second product collection type
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "name": {
          "de_DE": "Name de",
          "en_GB": "Name en"
        }
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_collection_type_3"

  Scenario: Create product collection type (only code)
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
         "code": "TEXT_@@random_code@@"
      }
      """
    Then the response status code should be 201

  Scenario: Create product collection type (wrong not correct code)
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
        "code": "TEXT/. .,.]_@@random_code@@"
      }
      """
    Then the response status code should be 400

  Scenario: Create product collection type (wrong not correct name)
    When I send a POST request to "/api/v1/en_GB/collections/type" with body:
      """
      {
        "code": "TEXT_@@random_code@@",
        "name": {
          "de_DE": "Bwuqy8IsaW6yeKGxTfuhpFvd56SYuXr3CvEgXMCTZ94NhTKzuOZKCLL93K1SQfoVdro3uIrZzwaOPbsro3DLHkSu64nknsdZbIWCA5tX47uP5a4LNNQQquATqdKp8rcxgMpMv9Xp3qvqfd5oUHuwcIzpBuQAyYvCNMPOxdmsXISqt42fZ9U0xvuC31qhXqRJiqUKLqBZWZiOhMQRZTBjApGyXd7V8pXctjI2IANx2fNnprX6RGiyV0Qb8ABAGlya",
          "en_GB": "Name en"
        }
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection type (not found)
    When I send a PUT request to "/api/v1/en_GB/collections/type/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Update product collection type (no content)
    When I send a PUT request to "/api/v1/en_GB/collections/type/@product_collection_type_1@" with body:
      """
      {
      }
      """
    Then the response status code should be 400

  Scenario: Update product collection type
    When I send a PUT request to "/api/v1/en_GB/collections/type/@product_collection_type_1@" with body:
      """
      {
        "name": {
          "de_DE": "Name de",
          "en_GB": "New Name en"
        }
      }
      """
    Then the response status code should be 204

  Scenario: Request product collection type (not found)
    When I send a GET request to "/api/v1/en_GB/collections/type/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Request product collection type
    When I send a GET request to "/api/v1/en_GB/collections/type/@product_collection_type_1@"
    Then the response status code should be 200
    And the JSON node "name.en_GB" should be equal to the string "New Name en"

  Scenario: Get product collection type (order by code)
    When I send a GET request to "/api/v1/en_GB/collections/type?field=code"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection type (order by name)
    When I send a GET request to "/api/v1/en_GB/collections/type?field=name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"
    And the JSON node "collection[0].code" should exist

  Scenario: Get product collection type (filter by code)
    When I send a GET request to "/api/v1/en_GB/collections/type?limit=25&offset=0&filter=code=text_"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection type (filter by null code)
    When I send a GET request to "/api/v1/en_GB/collections/type?limit=25&offset=0&filter=code="
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/0/"

  Scenario: Get product collection type (filter by name)
    When I send a GET request to "/api/v1/en_GB/collections/type?limit=25&offset=0&filter=name=Name"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get product collection type (order ASC)
    When I send a GET request to "/api/v1/en_GB/collections/type?limit=50&offset=0&order=ASC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Get products collection type  (order DESC)
    When I send a GET request to "/api/v1/en_GB/collections/type?limit=50&offset=0&order=DESC"
    Then the JSON should be valid according to the schema "module/grid/features/gridSchema.json"
    And the JSON node "info.filtered" should match "/[^0]/"

  Scenario: Delete product collection type (not found)
    When I send a DELETE request to "/api/v1/en_GB/collections/type/@@static_uuid@@"
    Then the response status code should be 404

  Scenario: Delete product collection type
    When I send a DELETE request to "/api/v1/en_GB/collections/type/@product_collection_type_2@"
    Then the response status code should be 204

  Scenario: Request product collection type after deletion
    When I send a GET request to "/api/v1/en_GB/collections/type/@product_collection_type_2@"
    Then the response status code should be 404


