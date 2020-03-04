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
    Given current authentication token
    And I attach "module/importer-magento-1/features/magento-1-test.csv" to the request as upload
    And the following form parameters are set:
      | name      | value       |
      | source_id | @source_id@ |
    When I request "/api/v1/EN/imports/upload" using HTTP POST
    Then created response is received
    And remember response param "id" as "source_id"

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


