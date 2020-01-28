Feature: Account module

  Scenario: Get profile log (order by author)
    Given current authentication token
    When I request "/api/v1/EN/profile/log?field=author" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (order by recorded_at)
    Given current authentication token
    When I request "/api/v1/EN/profile/log?field=recorded_at" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (order by event)
    Given current authentication token
    When I request "/api/v1/EN/profile/log?field=event" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (filter by time)
    Given current authentication token
    When I request "/api/v1/EN/profile/log?limit=25&offset=0&filter=recorded_at%3D2019" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (filter by author)
    Given current authentication token
    When I request "/api/v1/EN/profile/log?limit=25&offset=0&filter=author%3DSystem" using HTTP GET
    Then grid response is received

  Scenario: Get profile log (not authorized)
    When I request "/api/v1/EN/profile/log" using HTTP GET
    Then unauthorized response is received

  Scenario: Get accounts log (order by author)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?field=author" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"author"/
    """

  Scenario: Get accounts log (order by recorded_at)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?field=recorded_at" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"recorded_at"/
    """

  Scenario: Get accounts log (order by event)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?field=event" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """
    And the response body matches:
    """
      /"event:EN"/
    """

  Scenario: Get accounts log (filter by time)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get accounts log (filter by null time)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at=" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get accounts log (filter by time lower or equal 2000-01-01)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at<=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get accounts log (filter by time lower or equal 2000-01-01)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at>=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get accounts log (filter by time greater or equal 2050-01-01)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at>=2050-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": 0/
    """

  Scenario: Get accounts log (filter by time greater or equal 2050-01-01)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at<=2050-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get accounts log (filter by time in range 2000-01-01 -2050-01-01)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=recorded_at<=2050-01-01;recorded_at>=2000-01-01" using HTTP GET
    Then grid response is received
    And the response body matches:
    """
      /"filtered": [^0]/
    """

  Scenario: Get accounts log (filter by author)
    Given current authentication token
    When I request "/api/v1/EN/accounts/log?limit=25&offset=0&filter=author%3DSystem" using HTTP GET
    Then grid response is received

  Scenario: Get accounts log (not authorized)
    When I request "/api/v1/EN/accounts/log" using HTTP GET
    Then unauthorized response is received
