Feature: Export Profile Magento 2 CSV

  Background:
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

  Scenario: Get configuration with magento 2 csv
    When I send a GET request to "/api/v1/en_GB/channels/magento-2-csv/configuration"
    Then the response status code should be 200

  Scenario: Post Create Channel to magento 2 csv
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "filename": "m2.csv",
          "default_language": "en_GB"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "channel_id"

  Scenario: Post Create Channel to magento 2 csv no file name
    When I send a POST request to "/api/v1/en_GB/channels" with body:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv",
          "default_language": "en_GB"
        }
      """
    Then the response status code should be 400

  Scenario: Get export profile
    When I send a GET request to "/api/v1/en_GB/channels/@channel_id@"
    Then the response status code should be 200

  Scenario: Update magento 2 Channel
    When I send a PUT request to "/api/v1/en_GB/channels/@channel_id@" with body:
      """
        {
          "type": "magento-2-csv",
          "name": "Magento 2 csv Zmiana",
          "default_language": "en_GB",
          "filename": "maaa2.csv"
        }
      """
    Then the response status code should be 204

  Scenario: Delete export profile
    When I send a DELETE request to "/api/v1/en_GB/channels/@channel_id@"
    Then the response status code should be 204
