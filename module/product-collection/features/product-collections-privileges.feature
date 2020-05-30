Feature: Product collection privileges

  Scenario: Create product collection type (not authorized)
    When I send a POST request to "/api/v1/en/collections/type"
    Then the response status code should be 401

  Scenario: Get product collection type (not authorized)
    When I send a GET request to "/api/v1/en/collections/type"
    Then the response status code should be 401

  Scenario: Get product collection type (not authorized)
    When I send a GET request to "/api/v1/en/collections/type"
    Then the response status code should be 401

  Scenario: Request product collection type (not authorized)
    When I send a PUT request to "/api/v1/en/collections/type/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Update product collection type (not authorized)
    When I send a PUT request to "/api/v1/en/collections/type/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Delete product collection type (not authorized)
    When I send a DELETE request to "/api/v1/en/collections/type/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Create product collection (not authorized)
    When I send a POST request to "/api/v1/en/collections"
    Then the response status code should be 401

  Scenario: Get product collection (not authorized)
    When I send a GET request to "/api/v1/en/collections"
    Then the response status code should be 401

  Scenario: Update product collection (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Request product collection (not authorized)
    When I send a GET request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Delete product collection (not authorized)
    When I send a DELETE request to "/api/v1/en/collections/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Create product collection element (not authorized)
    When I send a POST request to "/api/v1/en/collections/@@static_uuid@@/elements"
    Then the response status code should be 401

  Scenario: Update product collection element (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@/elements/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Request product collection element (not authorized)
    When I send a PUT request to "/api/v1/en/collections/@@static_uuid@@/elements/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Delete product collection element (not authorized)
    When I send a DELETE request to "/api/v1/en/collections/@@static_uuid@@/elements/@@static_uuid@@"
    Then the response status code should be 401

  Scenario: Get product collection element (not authorized)
    When I send a GET request to "/api/v1/en/collections/@@static_uuid@@/elements"
    Then the response status code should be 401