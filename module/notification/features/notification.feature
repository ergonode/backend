Feature: Notification module

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get notifications
    When I send a GET request to "/api/v1/profile/notifications"
    Then the response status code should be 200

  Scenario: Mark all notifications
    When I send a POST request to "/api/v1/profile/notifications/mark-all"
    Then the response status code should be 202

  Scenario: Create template
    When I send a POST request to "/api/v1/en_GB/templates" with body:
      """
      {
        "name": "@@random_md5@@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "product_template_id"

  Scenario: Create first product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
              }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_1"

  Scenario: Create second product
    When I send a POST request to "/api/v1/en_GB/products" with body:
      """
      {
        "sku": "SKU_@@random_code@@",
        "type": "SIMPLE-PRODUCT",
        "templateId": "@product_template_id@"
              }
      """
    Then the response status code should be 201
    And store response param "id" as "product_id_2"

  Scenario: Create delete batch action for product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
         "type":"PRODUCT_DELETE",
         "filter":{
            "ids":{
               "list":[
                  "@product_id_1@"
               ],
               "included":true
            }
         }
      }
    """
    Then the response status code should be 201

  Scenario: Create delete batch action for product
    And I send a "POST" request to "/api/v1/en_GB/batch-action" with body:
    """
      {
         "type":"PRODUCT_DELETE",
         "filter":{
            "ids":{
               "list":[
                  "@product_id_2@"
               ],
               "included":true
            }
         }
      }
    """
    Then the response status code should be 201

  Scenario: Check Notifications
    When I send a GET request to "/api/v1/profile/notifications/check"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | unread | 2 |

  Scenario: Get notification grid
    When I send a GET request to "/api/v1/profile/notifications?order=DESC&field=created_at"
    Then the response status code should be 200
    Then the JSON should be valid according to the schema "grid/features/gridSchema.json"
    And store response param "collection[0].id" as "notification_id"

  Scenario: Mark one notification as read
    When I send a POST request to "/api/v1/profile/notifications/@notification_id@/mark"
    Then the response status code should be 202

  Scenario: Mark one notification as read (wrong id)
    When I send a POST request to "/api/v1/profile/notifications/not_uid/mark"
    Then the response status code should be 400

  Scenario: Check Notifications
    When I send a GET request to "/api/v1/profile/notifications/check"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | unread | 1 |

  Scenario: Mark all notifications
    When I send a POST request to "/api/v1/profile/notifications/mark-all"
    Then the response status code should be 202

  Scenario: Check Notifications
    When I send a GET request to "/api/v1/profile/notifications/check"
    Then the response status code should be 200
    And the JSON nodes should contain:
      | unread | 0 |
