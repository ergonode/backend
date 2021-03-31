Feature: Deepl module

#  Scenario: Get translation deepl
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&source_language=PL&target_language=en" using HTTP GET
#    Then the response code is 200
#
#  Scenario: Get translation deepl (not authenticated)
#    When I request "/api/v1/translation/deepl" using HTTP GET
#    Then unauthorized response is received
#
#  Scenario: Get translation deepl (without any arguments)
#    Given current authentication token
#    When I request "/api/v1/translation/deepl" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (too short content)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=ul&source_language=PL&target_language=en" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (without content)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?source_language=PL&target_language=en" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (wrong source language)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&source_language=ZZ&target_language=en" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (empty source language)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&source_language=&target_language=en" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (without source language)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&target_language=en" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (wrong target language)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&source_language=PL&target_language=ZZ" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (empty target language)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&source_language=PL&target_language=" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl (without target language)
#    When current authentication token
#    When I request "/api/v1/translation/deepl?content=kat&source_language=PL" using HTTP GET
#    Then validation error response is received
#
#  Scenario: Get translation deepl usage
#    When current authentication token
#    When I request "/api/v1/translation/usage" using HTTP GET
#    Then the response code is 200
#
#  Scenario: Get translation deepl (not authenticated)
#    When I request "/api/v1/translation/usage" using HTTP GET
#    Then unauthorized response is received
