Feature: Condition Product has template

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Create template
    When I send a "POST" request to "/api/v1/EN/templates"
      """
      {
        "name": "@@random_md5@@",
        "image": null,
        "defaultText": null,
        "defaultImage": null,
        "elements": [
        ]
      }
      """
    Then the response status code should be 201
    And store response param "id" as "template_id"

  Scenario: Get product has template condition
    When I send a "GET" request to "/api/v1/EN/conditions/PRODUCT_HAS_TEMPLATE_CONDITION"
    Then the response status code should be 200

  Scenario Outline: Post new valid product has template condition set
    When I send a "POST" request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_TEMPLATE_CONDITION",
              "operator": <operator>,
              "template_id": "@template_id@"
            }
          ]
        }
      """
    Then the response status code should be 201
    Examples:
      | operator  |
      | "HAS"     |
      | "NOT_HAS" |

  Scenario Outline: Post new invalid product has template condition set
    When I send a "POST" request to "/api/v1/EN/conditionsets" with body:
      """
        {
          "conditions": [
            {
              "type": "PRODUCT_HAS_TEMPLATE_CONDITION",
              "operator": <operator>,
              "template_id": <template_id>
            }
          ]
        }
      """
    Then the response status code should be 400
    Examples:
      | operator  | template_id       |
      | "HAS"     | "@@static_uuid@@" |
      | "HAS"     | ""                |
      | "HAS"     | null              |
      | null      | 1                 |
      | "INVALID" | 2                 |
      | ""        | 1                 |