Feature: Category module

  Scenario: Create Magento 1 CSV Source
    Given current authentication token
    Given the request body is:
      """
      {
        "type": "magento-1-csv",
        "name": "name",
        "host": "https://husse-eu.global.ssl.fastly.net/media/catalog/product/cache/8/image/9df78eab33525d08d6e5fb8d27136e95",
        "import" : {
           "templates": true,
           "attributes": true,
           "categories": true,
           "multimedia": true,
           "products": true
        },
        "default_language": "EN",
        "languages": [
          {
            "store": "poland_pl",
            "language": "PL"
          },
           {
            "store": "france_fr",
            "language": "FR"
          },
          {
            "store": "hungary_hu",
            "language": "HU"
          },
          {
            "store": "serbia_sr",
            "language": "SR"
          },
          {
            "store": "spain_es",
            "language": "ES"
          },
          {
            "store": "turkey_tr",
            "language": "TR"
          }
        ]
      }

      """
    When I request "/api/v1/EN/sources" using HTTP POST
    Then created response is received
    And remember response param "id" as "source_id"
    And sleep

  Scenario: Upload magento 1 test import file
    Given current authentication token
    And I attach "module/importer-magento-1/features/2019-02-12-husse-prod-sample.csv" to the request as upload
    And the following form parameters are set:
      | name        | value         |
      | source_id   | @source_id@ |
    When I request "/api/v1/EN/imports/upload" using HTTP POST
    Then created response is received
    And remember response param "id" as "source_id"
    And sleep

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


