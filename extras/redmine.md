
Collection resources and pagination

The response to a GET request on a collection resources (eg. /issues.xml, /users.xml) generally won't return all the objects available in your database. Redmine 1.1.0 introduces a common way to query such resources using the following parameters:

    offset: the offset of the first object to retrieve
    limit: the number of items to be present in the response (default is 25, maximum is 100)

Examples:

GET /issues.xml
=> returns the 25 first issues

GET /issues.xml?limit=100
=> returns the 100 first issues

GET /issues.xml?offset=30&limit=10
=> returns 10 issues from the 30th


Responses to GET requests on collection resources provide information about the total object count available in Redmine and the offset/limit used for the response. Examples:

GET /issues.xml

<issues type="array" total_count="2595" limit="25" offset="0">
  ...
</issues>

GET /issues.json

{ "issues":[...], "total_count":2595, "limit":25, "offset":0 }

