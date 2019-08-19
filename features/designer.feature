Feature: Testing designer component

  Background:
    When I login as "test@ergonode.com" with "123"

  Scenario: I get designer templates
    When I get designer templates
    Then I get 200 result code

  Scenario: I create designer templates
    When I get attribute group dictionary
    When I fill template correctly
    When I create template
    Then I get 201 result code
    Then I get template Id
    When I fill select attribute correctly
    Then I create attribute
    Then I get 201 result code
    Then I get attribute Id
    Then I add attribute to template
    When I fill text attribute correctly
    Then I create attribute
    Then I get 201 result code
    Then I get attribute Id
    Then I add attribute to template
    When I fill date attribute correctly
    Then I create attribute
    Then I get 201 result code
    Then I get attribute Id
    Then I add attribute to template
    Then I update template
    Then I get 200 result code
#    When I delete template
#    Then I get 204 result code
