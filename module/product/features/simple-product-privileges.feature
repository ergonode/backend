Feature: Simple product privileges

  Scenario: Create product (not authorized)
    When I send a POST request to "/api/v1/en_GB/products"
    Then the response status code should be 401

  Scenario: Update product (not authorized)
    When I send a PUT request to "/api/v1/en_GB/products/@product@"
    Then the response status code should be 401

  Scenario: Get product (not authorized)
    When I send a GET request to "/api/v1/en_GB/products/@product@"
    Then the response status code should be 401

  Scenario: Get product collections  (not authorized)
    When I send a GET request to "/api/v1/en_GB/products/@product@/collections"
    Then the response status code should be 401

  Scenario: Delete product (not authorized)
    When I send a DELETE request to "/api/v1/en_GB/products/@product@"
    Then the response status code should be 401

  Scenario: Get products (not authorized)
    When I send a GET request to "/api/v1/en_GB/products"
    Then the response status code should be 401