Feature: Workflow

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"


  Scenario: Set status order
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
    "cfdf2acf-db2e-553f-9ae1-0e2809e572c1",
    "b63f2345-3679-5eb6-a013-f41d41b62f66",
    "5d47ce05-9008-517c-a9ac-58c93ac0924b",
    "380ea12b-2f03-59ae-a625-702a165ff1ca",
    "163081ad-1ea6-5c1a-b3aa-92cebf07a179",
    "c32f967b-1cb2-56a6-8873-e8af9d20f0e6"
            ]
  }
      """
    Then the response status code should be 204

  Scenario: Set status order (not all statuses)
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
    "b63f2345-3679-5eb6-a013-f41d41b62f66",
    "5d47ce05-9008-517c-a9ac-58c93ac0924b",
    "380ea12b-2f03-59ae-a625-702a165ff1ca",
    "163081ad-1ea6-5c1a-b3aa-92cebf07a179",
    "c32f967b-1cb2-56a6-8873-e8af9d20f0e6"
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
    "b63f2345-3679-5eb6-a013-f41d41b62f66",
    "5d47ce05-9008-517c-a9ac-58c93ac0924b",
    "380ea12b-2f03-59ae-a625-702a165ff1ca",
    "cfdf2acf-db2f-553f-9ae1-0e2809e572c1",
    "163081ad-1ea6-5c1a-b3aa-92cebf07a179",
    "c32f967b-1cb2-56a6-8873-e8af9d20f0e6"
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
    "b63f2345-3679-5eb6-a013-f41d41b62f66",
    "5d47ce05-9008-517c-a9ac-58c93ac0924b",
    "380ea12b-2f03-59ae-a625-702a165ff1ca",
    "163081ad-1ea6-5c1a-b3aa-92cebf07a179",
    "test",
    "c32f967b-1cb2-56a6-8873-e8af9d20f0e6"
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
      | collection[0].id | cfdf2acf-db2e-553f-9ae1-0e2809e572c1 |
      | collection[1].id | b63f2345-3679-5eb6-a013-f41d41b62f66 |

  Scenario: Set status order (changing order)
    When I send a POST request to "/api/v1/en_GB/status/order" with body:
      """
   {
  "statusIds": [
    "b63f2345-3679-5eb6-a013-f41d41b62f66",
    "5d47ce05-9008-517c-a9ac-58c93ac0924b",
    "380ea12b-2f03-59ae-a625-702a165ff1ca",
    "163081ad-1ea6-5c1a-b3aa-92cebf07a179",
    "c32f967b-1cb2-56a6-8873-e8af9d20f0e6",
    "cfdf2acf-db2e-553f-9ae1-0e2809e572c1"
            ]
  }
      """
    Then the response status code should be 204

  Scenario: Get status grid
    When I send a GET request to "/api/v1/en_GB/status"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | collection[0].id | b63f2345-3679-5eb6-a013-f41d41b62f66 |
      | collection[5].id | cfdf2acf-db2e-553f-9ae1-0e2809e572c1 |
