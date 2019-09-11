Feature: Segment

  Scenario: Create segment (not authorized)
    When I request "/api/v1/EN/segments" using HTTP POST
    Then unauthorized response is received

  Scenario: Create segment
    Given current authentication token
    Given the request body is:
      """
      {
        "code": "@@random_md5@@",
        "name": {
          "PL": "Segment",
          "EN": "Segment"
        },
        "description": {
          "PL": "Opis segmentu",
          "EN": "Segment description"
        }
      }
      """
    When I request "/api/v1/EN/segments" using HTTP POST
    Then created response is received
    And remember response param "id" as "segment"

  Scenario: Update segment (not authorized)
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then unauthorized response is received

  Scenario: Update segment (not found)
    Given current authentication token
    When I request "/api/v1/EN/segments/@@static_uuid@@" using HTTP PUT
    Then not found response is received

  Scenario: Update segment
    Given current authentication token
    Given the request body is:
      """
      {
        "name": {
          "PL": "Segment (changed)",
          "EN": "Segment (changed)"
        },
        "description": {
          "PL": "Opis segmentu (changed)",
          "EN": "Segment description (changed)"
        }
      }
      """
    When I request "/api/v1/EN/segments/@segment@" using HTTP PUT
    Then empty response is received

  # TODO Segment grid with all options
  # TODO Get segment
  # TODO Create segment with invalid data
  # TODO Update segment with invalid data
