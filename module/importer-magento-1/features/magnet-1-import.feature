Feature: Category module

  Scenario: Upload magento 1 test import file
    Given current authentication token
    And I attach "module/importer-magento-1/features/magento-1-test.csv" to the request as upload
    And the following form parameters are set:
      | name | value                  |
      | source_type | magento-1-csv |
    When I request "/api/v1/EN/imports/upload" using HTTP POST
    Then created response is received
    And remember response param "id" as "source_id"

  Scenario: Get magento 1 configuration for given source
    Given current authentication token
    When I request "/api/v1/EN/sources/@source_id@/configuration"
    Then the response code is 200
    And print last api response

  Scenario: Post magento 1 configuration for given source
    Given current authentication token
    Given the request body is:
      """
      {
          "columns": [
            {
                "column":"sku",
                "code" : "sku",
                "type": "TEXT",
                "imported" : true
            }
          ]
      }
      """
    When I request "/api/v1/EN/sources/@source_id@/configuration" using HTTP POST
    Then created response is received
    And remember response param "id" as "import_id"

