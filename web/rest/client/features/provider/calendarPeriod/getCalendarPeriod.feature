Feature: Retrieve calendar periods
  In order to manage calendar periods
  As a client admin
  I need to be able to retrieve them through the API.

  @createSchema
  Scenario: Retrieve the calendar periods json list
    Given I add Company Authorization header
     When I add "Accept" header equal to "application/json"
      And I send a "GET" request to "calendar_periods"
     Then the response status code should be 200
      And the response should be in JSON
      And the header "Content-Type" should be equal to "application/json; charset=utf-8"
      And the JSON should be equal to:
    """
      [
          {
              "startDate": "2019-01-01",
              "endDate": "2019-10-01",
              "routeType": "number",
              "id": 1
          }
      ]
    """

  Scenario: Retrieve certain calendar json
    Given I add Company Authorization header
     When I add "Accept" header equal to "application/json"
      And I send a "GET" request to "calendar_periods/1"
     Then the response status code should be 200
      And the response should be in JSON
      And the header "Content-Type" should be equal to "application/json; charset=utf-8"
      And the JSON should be like:
    """
      {
          "startDate": "2019-01-01",
          "endDate": "2019-10-01",
          "routeType": "number",
          "numberValue": "911",
          "id": 1,
          "calendar": {
              "name": "testCalendar",
              "id": 1,
              "company": 1
          },
          "locution": null,
          "extension": null,
          "voiceMailUser": null,
          "numberCountry": {
              "code": "AD",
              "countryCode": "+376",
              "id": 1,
              "name": {
                  "en": "Andorra",
                  "es": "Andorra",
                  "ca": "Andorra",
                  "it": "Andorra"
              },
              "zone": {
                  "en": "Europe",
                  "es": "Europa",
                  "ca": "Europa",
                  "it": "Europe"
              }
          }
      }
    """
