API
===

Two types of objects are supported, authors and articles.

Authors:
--------

 - name: string, used to identify the author in artciles
 - id: integer, numeric ID of the author

Articles:
---------

 - title: string, title of the article
 - content: string, content of the article
 - created_at: datetime, creation date of the article
 - author_id: integer, ID of the author of the article
 - id: integer, numeric ID of the article

Endpoints:
----------

 - GET /authors/[$id] - returns the data for an author, or if no $id is specified, all authors.
 - GET /articles/[$id] - returns the full data for a single article, or if $id is not specified, a summary of all articles.
 - POST /authors/ - creates an author and returns it's data
 - POST /articles/ - creates an article and returns it's data
 - PUT /articles/$id - updates an existing article and returns it's data
 - DELETE /articles/$id - deletes an existing article and returns a list of all articles.

Examples:
---------

GET /authors

```[{"id":1,"name":"Frank Matthews"},{"id":2,"name":"James Franklin"}]```

GET /authors/1

```{"id":1,"name":"Frank Matthews"}```

POST /articles `{"title":"a","author_id":1,"content":"b"}`

```{"id":1,"url":"/articles/1","title":"a","content":"b","author":"Frank Matthews","createdAt":"2016-01-01T12:34:56"}```

