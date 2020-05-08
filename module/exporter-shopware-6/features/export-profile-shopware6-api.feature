Feature: Export Profile Shopware 6 API

  Scenario: Get configuration with Shopware 6 API
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a GET request to "/api/v1/EN/export-profile/shopware-6-api/configuration"
    Then the response status code should be 200

  Scenario: Post Create Export profile to Shopware 6 API
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/export-profile" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api",
          "host": "https://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en"
        }
      """
    Then the response status code should be 201
    And store response param "id" as "export_profile_id"

  Scenario: Update Export Profile
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a PUT request to "/api/v1/en/export-profile/@export_profile_id@" with body:
      """
        {
          "type": "shopware-6-api",
          "name": "Shopware 6 api - TEST",
          "host": "https://192.168.1.100:8000",
          "client_id": "SWIAMURTYTK0R2RQEFBVUNPDTQ",
          "client_key": "Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08",
          "default_language": "en"
        }
      """
    Then the response status code should be 204

  Scenario: Create channel to Shopware6
    Given I am Authenticated as "test@ergonode.com"
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a POST request to "/api/v1/en/channels" with body:
      """
      {
        "name": "Shopware 6 Default",
        "export_profile_id": "@export_profile_id@"
      }
      """
    Then the response status code should be 201
    And store response param "id" as "channel"
