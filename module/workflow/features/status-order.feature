Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get statuses
    When I send a GET request to "/api/v1/en_GB/status"
    Then the response status code should be 200
    And store response param "collection[0].id" as "status_id_1"
    And store response param "collection[1].id" as "status_id_2"
    And store response param "collection[2].id" as "status_id_3"
    And store response param "collection[3].id" as "status_id_4"
    And store response param "collection[4].id" as "status_id_5"
    And store response param "collection[5].id" as "status_id_6"

  Scenario: Set status order
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
          "@status_id_6@",
          "@status_id_5@",
          "@status_id_4@",
          "@status_id_3@",
          "@status_id_2@",
          "@status_id_1@"
            ]
  }
      """
    Then the response status code should be 204

  Scenario: Set status order (not all statuses)
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
        "@status_id_6@",
        "@status_id_5@",
        "@status_id_4@",
        "@status_id_3@"
            ]
  }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.statusIds | Doesn't contain all status ids |

  Scenario: Set status order (one status Id is wrong)
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
          "@status_id_6@",
          "@status_id_5@",
          "@status_id_4@",
          "@@random_uuid@@",
          "@status_id_2@",
          "@status_id_1@"
            ]
  }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.statusIds.element-3[0] | Status not exists |

  Scenario: Set status order (one invalid UUID)
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
          "@status_id_6@",
          "@status_id_5@",
          "@status_id_4@",
          "@status_id_3@",
          "test",
          "@status_id_1@"
            ]
  }
      """
    Then the response status code should be 400
    And the JSON nodes should contain:
      | errors.statusIds.element-4[0] | This is not a valid UUID |

  Scenario: Get status grid
    When I send a GET request to "/api/v1/en_GB/status"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @status_id_6@ |
      | collection[1].id | @status_id_5@ |

  Scenario: Set status order (changing order)
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
          "@status_id_1@",
          "@status_id_2@",
          "@status_id_3@",
          "@status_id_4@",
          "@status_id_5@",
          "@status_id_6@"
            ]
  }
      """
    Then the response status code should be 204

  Scenario: Get status grid
    When I send a GET request to "/api/v1/en_GB/status"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | @status_id_1@ |
      | collection[5].id | @status_id_6@ |
