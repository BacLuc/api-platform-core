Feature: Reply to preflight requests
  If the web frontend and the api are not on the same host,
  the browser sends preflight requests to know if
  requests with this method are allowed.

  Scenario: A preflight requests is answered with the correct headers
    When I send a "OPTIONS" request to "/"
    Then the response status code should be 200
    And the response should be empty
