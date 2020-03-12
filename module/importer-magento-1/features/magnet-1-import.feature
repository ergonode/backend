Feature: Category module

  Scenario: Create Magento 1 CSV Source
    Given current authentication token
    Given the request body is:
      """
      {
        "type": "magento-1-csv",
        "name": "name",
        "host": "http://test.host",
        "import" : {
           "templates": true,
           "attributes": true,
           "categories": true,
           "multimedia": false,
           "products": true
        },
        "default_language": "EN",
        "languages": []
      }
      """
    When I request "/api/v1/EN/sources" using HTTP POST
    Then created response is received
    And remember response param "id" as "source_id"

  Scenario: Upload magento 1 test import file
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "multipart/form-data"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/EN/imports/upload" with params:
      | key    | value                   |
      | source_id | @source_id@          |
      | upload    | @magento-1-test.csv  |
    Then the response status code should be 201
    And the JSON node "id" should exist
    And store response param "id" as "source_id"

#  Scenario: Get magento 1 configuration for given source
#    Given current authentication token
#    When I request "/api/v1/EN/sources/@source_id@/configuration"
#    Then the response code is 200

#  Scenario: Post magento 1 configuration for given source
#    Given current authentication token
#    Given the request body is:
#      """
#      {
#        "languages": [
#          {
#             "store": "france",
#             "language": "FR"
#          }
#        ]
#      }
#      """
#    When I request "/api/v1/EN/sources/@source_id@/configuration" using HTTP POST
#    And print last api response
#    Then the response code is 200
#    And remember response param "id" as "import_id"


