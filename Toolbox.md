

# Toolbox

## Subsystems

Our system is divided into two subsystems who are independent of each other. 

- Our frontend is a web based application written in Angular 4 based on typescript. 
- Our backend is written in PHP.

Both of the systems are hosted on Amazon AWS.

## Logical Data Model

![sequence_upvote](https://i.imgur.com/dFDvmi8.jpg)

## Use Case Diagram

![use_case_diagram](https://i.imgur.com/WFzoVXY.jpg)

## Use Case description

##### Create user

As a non user I want to be able to create myself as a user in the system so I can log in and post, comment, upvote and downvote.

##### Get posts
As both a non user and a user I want to be able to get all posts so that I can read my posts or other users posts.

##### Get comments for posts
As both a non user and a user i want to be able to retreive comments for posts so I can read what I or other users have commented on the posts.

##### Login
As a user I want to be able to login to the system so I am able to write posts/comments, downvote/upvote posts or change my profile.

##### Update user
As a user I want to be able to update my profile description so that the system may know of any personal data changes.

##### Post post
As a user i want to be able to post a post so that other users may read my post.

##### Comment on post
As a user I want to be able to comment on a post in order to let other users read my input to the post.

##### Upvote Post
As a user i want to be able to upvote a post so that the system may tell other user that I like it.

##### Downvote Post
As a user i want to be able to downvote a post so that the system may tell other user that I dislike it.

### Description of actors

##### Non user:

- A person who can view content, but doesn't have the access to do anything else.

##### User:

* A person who can view content, and also have access to post, comment, upvote, downvote and change own profile.

## Sequence diagrams

##### Create user

![sequence_create_user](https://i.imgur.com/oRrg3Q0.jpg)

##### Get Posts

![sequence_get_posts](https://i.imgur.com/hYIRBnI.jpg)

##### Get Comments for post

![sequence_get_comments_for_post](https://i.imgur.com/emYEown.jpg)

##### Login

![sequence_login](https://i.imgur.com/YT6UjdO.jpg)

##### Update User

![sequence_update_user](https://i.imgur.com/F3SGCHQ.jpg)

##### Post post

![sequence_create_post](https://i.imgur.com/ScQTFMY.jpg)

##### Comment on post

![sequence_get_comments_for_post](https://i.imgur.com/frO69hd.jpg)

##### Upvote Post

![sequence_upvote](https://i.imgur.com/61Grtpr.jpg)

##### Downvote Post

![sequence_upvote](https://i.imgur.com/5p2rUul.jpg)

## Routes

### POST /login

#### Description

Attempts to perform a log in, based on a given email and password. If the login succeeds, a JSON web token is returned.

#### Required parameters

The following parameters must be contained in the body of the request:

- **email** - The email that you want to log in with
- **password** - The password that you want to log in with

#### Format

```json
{
	"email": "test@test.com",
	"password": "pass"
}
```

#### Responses

##### 200 OK

Upon matching credentials, the following response is sent with a JSON web token, which can be used to authorize privileges on all subsequent requests.

```json
{
    "code": 0,
    "data": "<token>"
}
```

##### 400 Bad Request

If the client performs a request which is, for any reason, not understandable by the backend, the following response is sent with a status code of 400.

```json
{
    "code": 4,
    "message": "Validation error",
    "errors": [
       "<error_message>",
       "<error_message>"
    ]
}
```

##### 401 Unauthorized

If the login credentials provided does not match, the following response is sent with a status code of 401.

```json
{
    "code": 1,
    "message": "Mismatching credentials"
}
```

### POST /sign-up

#### Description

Attempts to sign up a user, based on a given email, alias and password. If the sign-up succeeds, a JSON web token is returned.

#### Required parameters

The following parameters must be contained in the body of the request:

- **email** - The email that you want to sign up with
- **password** - The password that you want to sign up with
- **alias** - The alias that you want to sign up with

#### Format

```json
{
	"email": "test@test.com",
	"password": "pass",
	"alias": "alias"
}
```

#### Responses

##### 200 OK

Upon success, the user is saved in the system and the following response is sent with a JSON web token, which can be used to authorize privileges on all subsequent requests.

```json
{
    "code": 0,
    "data": "<token>"
}
```

##### 400 Bad Request

If the client performs a request which is, for any reason, not understandable by the backend, the following response is sent with a status code of 400.

```json
{
    "code": 4,
    "message": "Validation error",
    "errors": [
       "<error_message>",
       "<error_message>"
    ]
}
```

##### 409 Conflict

If the email or alias is already registered within the system, the following response is sent with a status code of 409.

```json
{
    "code": 7,
    "message": "A user already exists with that e-mail or alias"
}
```

### GET /posts

#### Description

Retrieves a list of posts.

#### Optional parameters

The following parameters must be contained in the body of the request:

- **limit** - A positive integer indicating the maximum number of posts to be returned. *(Default: 5)*
- **page** - A positive integer indicating which page you want. For example, if your limit is set to 5, and your page is set to 2, the backend will skip the first 5 posts and give you the next 5. *(Default: 1)*

#### Responses

##### 200 OK

Upon success, the user is saved in the system and the following response is sent with a JSON web token, which can be used to authorize privileges on all subsequent requests.

```json
{
    "code": 0,
    "data": {
        "has_more": true,
        "posts": [
            {
                "id": 1,
                "title": "Lorem ipsum dolor",
                "slug": "lorem-ipsum-dolor",
                "karma": 1337,
                "created_at": "2017-10-14T15:43:43Z",
                "spam": false,
                "content": "Lorem ipsum dolor",
                "author": {
                    "id": 1,
                    "karma": 1337,
                    "alias": "ChuckN0rr1s"
                }
            },
            {
                "id": 2,
                "title": "Lorem ipsum dolor",
                "slug": "lorem-ipsum-dolor-1",
                "karma": 1337,
                "created_at": "2017-10-14T15:43:43Z",
                "spam": false,
                "url": "https://loremipsum.com",
                "author": {
                    "id": 1,
                    "karma": 1337,
                    "alias": "ChuckN0rr1s"
                }
            }
        ]
    }
}
```

##### 400 Bad Request

If the client performs a request which is, for any reason, not understandable by the backend, the following response is sent with a status code of 400.

```json
{
    "code": 4,
    "message": "Invalid pagination parameters",
    "errors": [
        "<error_message>",
        "<error_message>"
    ]
}
```

### GET /posts/{slug}

#### Description

Retrieves a specific post.

#### Route parameters

The resource's identifier contains the following route parameters:

- **slug** - The URL slug of the post you wish to retrieve

#### Responses

##### 200 OK

Upon success, the user is saved in the system and the following response is sent with a JSON web token, which can be used to authorize privileges on all subsequent requests.

```json
{
    "code": 0,
    "data": {
        "post": {
            "id": 1,
            "title": "Lorem ipsum",
            "slug": "lorem-ipsum",
            "karma": 1337,
            "created_at": "2017-10-21T13:26:28Z",
            "spam": false,
            "content": "testing",
            "author": {
                "id": 1,
                "karma": 1337,
                "alias": "ChuckN0rr1s"
            }
        },
        "comments": {
            "has_more": false,
            "comments": [
				{
	                "id": 1,
	                "user_ref": 5,
	                "post_ref": 1,
	                "comment_ref": null,
	                "content": "Great news",
	                "karma": 1337,
	                "spam": false,
	                "created_at": "2017-10-14 15:28:13",
	                "author": {
	                    "id": 5,
	                    "karma": 1337,
	                    "alias": "JohnDoe42"
	                }
	            }
			]
        }
    }
}
```

##### 204 No content

If the client performs a request which has no matching results, a response with an empty body and a status code of 204 is returned.

### GET /comments/{post_id}

#### Description

Retrieves a list of comments for a specific post.

#### Route parameters

The resource's identifier contains the following route parameters:

- **slug** - The URL slug of the comments you wish to retrieve.

#### Optional parameters

The following parameters must be contained in the body of the request:

- **limit** - A positive integer indicating the maximum number of comments to be returned. *(Default: 5)*
- **page** - A positive integer indicating which page you want. For example, if your limit is set to 5, and your page is set to 2, the backend will skip the first 5 comments and give you the next 5. *(Default: 1)*

#### Responses

##### 200 OK

Upon success, the user is saved in the system and the following response is sent with a JSON web token, which can be used to authorize privileges on all subsequent requests.

```json
{
    "code": 0,
    "data": {
        "has_more": true,
        "comments": [
            {
                "id": 1,
                "user_ref": 5,
                "post_ref": 1,
                "comment_ref": null,
                "content": "Great news",
                "karma": 1337,
                "spam": false,
                "created_at": "2017-10-14 15:28:13",
                "author": {
                    "id": 5,
                    "karma": 1337,
                    "alias": "JohnDoe42"
                }
            }
        ]
    }
}
```

##### 400 Bad Request

If the client performs a request which is, for any reason, not understandable by the backend, the following response is sent with a status code of 400.

```json
{
    "code": 4,
    "message": "Invalid pagination parameters",
    "errors": [
        "<error_message>",
        "<error_message>"
    ]
}
```

### POST /post/upvote

#### Description

Attempts to upvote a post based on a post id. If the upvote is successful the upvoted post is returned as JSON.

#### Required headers

- This resource required you to be authorized. To authorize, simply include your JSON web token as a request header with the following format:

  ```json
  Authorization: Bearer <token>
  ```

#### Required parameters

- **post_ref** the id of the post being updated.

#### Format

```json
{
	"post_ref": 1
}
```

#### Responses

#### 200 OK

```json
{
    "code": 0,
    "data": {
        "id": 1,
        "title": "Microsofts Visual Studio Launches on Mac",
        "slug": "microsofts-visual-studio-launches-on-mac",
        "karma": "1",
        "created_at": "2017-10-11T12:31:27Z",
        "spam": false,
        "my_vote": 1,
        "domain": "lifehacker.com",
        "url": "https://lifehacker.com/microsofts-visual-studio-launches-on-mac-1789045481",
        "author": {
            "id": 4,
            "karma": -212,
            "alias": "zardoth"
        }
    }
}
```

#### 204 No Content

```json

```

#### 400 Bad Request

```json
{
    "code": 4,
    "message": "Validation error",
    "errors": [
        "Please provide a valid post reference for your vote"
    ]
}
```

#### 401 Unauthorized

```json
{
    "code": 5,
    "message": "You do not have permission to request this resource"
}
```

### POST /post/downvote

#### Description

Attempts to downvote a post based on a post id, and amount of karma. If the downvote is successful the downvoted post is returned as JSON.

#### Required headers

- This resource required you to be authorized. To authorize, simply include your JSON web token as a request header with the following format:

  ```json
  Authorization: Bearer <token>
  ```

#### Required parameters

- **post_ref** the id of the post being updated.

#### Format

```json
{
	"post_ref": 1
}
```

#### Responses

#### 200 OK

```json
{
    "code": 0,
    "data": {
        "id": 1,
        "title": "Microsofts Visual Studio Launches on Mac",
        "slug": "microsofts-visual-studio-launches-on-mac",
        "karma": "1",
        "created_at": "2017-10-11T12:31:27Z",
        "spam": false,
        "my_vote": 1,
        "domain": "lifehacker.com",
        "url": "https://lifehacker.com/microsofts-visual-studio-launches-on-mac-1789045481",
        "author": {
            "id": 4,
            "karma": -212,
            "alias": "zardoth"
        }
    }
}
```

#### 204 No Content

```json

```

#### 400 Bad Request

```json
{
    "code": 4,
    "message": "Validation error",
    "errors": [
        "Please provide a valid post reference for your vote"
    ]
}
```

#### 401 Unauthorized

```json
{
    "code": 5,
    "message": "You do not have permission to request this resource"
}
```

#### 405 Method Not Allowed

```json
{
    "code": 5,
    "message": "Your karma score is to low to be able to downvote, minimum 50 karma required."
}
```



## Work distribution

All the group met up and made this together so the work distribution is around 20% for everybody.