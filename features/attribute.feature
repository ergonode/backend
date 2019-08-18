Feature: Testing attribute component

  Background:
    When I login as "test@ergonode.com" with "123"
    And I get attribute group dictionary
    And I get 200 result code

  Scenario: I create correct text attribute
    When I fill text attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct textarea attribute
    When I fill textarea attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct select attribute
    When I fill select attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct multi select attribute
    When I fill multi select attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct date attribute
    When I fill date attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct multimedia attribute
    When I fill image attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct price attribute
    When I fill price attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

  Scenario: I create correct unit attribute
    When I fill unit attribute correctly
    And I create attribute
    And I get 201 result code
    And I get attribute Id
    And I update attribute
    Then I get 200 result code

# validation tests
  Scenario: I send incorrect date attribute format
    When I fill date attribute correctly
    And I set "Incorrect format" value to field "format"
    And I create attribute
    Then I get 400 result code

  Scenario Outline: I send text attribute with empty value for <field>
    When I fill text attribute correctly
    And I remove value from field <field>
    And I create attribute
    Then I get 400 result code

    Examples:
      | field               |
      | code                |
      | type                |
