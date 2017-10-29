# 1. Introduction

## A. Purpose of the system

The purpose of the system is to provide the user with a social news website similar to Reddit. The social news network allows users to post articles related to computer science and entrepreneurship. Furthermore, it allows registered users to create their own posts and participate in discussions by leaving replies on other people's posts and comments. 

## B. Scope of the system

The idea is to build a system that should mimic a number of selected functionalities of https://hackernews.com. A full list of features will be provided further down. 

## C. Objectives and success criteria of the project

1. The system should be available to anyone with an internet connection and a browser from a publicly visible server
2. The system should have an uptime of at least 95% over the course of a 2 month period
3. The system should be able to retrieve messages, even when the backend and database is down.

## D. Definitions, acronyms, and abbreviations

**Karma** - A user is rated with karma points which is earned when a user's article is either up or down voted. Each up vote increases points and down votes decreases points.

## E. References

Our primary reference is the assignment description supplied to us in the Large Systems Development class, located on the GitHub repository dedicated to the subject.

## F. Overview

The project will be focused on the description supplied to us, and will be worked on iteratively through the various assignments we will be given throughout the semester. While we do not have a current system to describe aside from the application we are supposed to imitate, we will have functional and nonfunctional requirements detailed for our proposed system.

# 2. Current System

There is currently no system in place, as this is a student project, and not a solution intended to replace any current systems.

# 3. Proposed System

## A. Overview

Our system will resemble the HackerNews website and include all of its basic features eg. posting articles, commenting, up-down voting, spam-flagging and karma ratings . The system will have a basic web based GUI which will be easy to use.

## B. Functional requirements

- Web based application
- All users can read content
- Registered users can post/comment on/up-vote/spam-flag articles
- Registered users with a karma score of 500+ can down vote articles
- All content must be persisted in a database
- All articles must be time stamped
- The system has to have a REST API

## C. Nonfunctional requirements

### a. Usability

The system will be a clone of Hackernews, and as such must have a simple graphical user interface that can be operated through most modern browsers. The simplicity of the graphical user interface is key, as we do not presume user familiarity with any similar systems, and will not be providing any sort of training in its usage.

The system must provide error messages to the user if an error has occurred, with a descriptive message to inform the user what went wrong. 

The system must present the different stories that have been posted in an orderly, compact yet understandable manner. 

The comments on any given story, along with the nested comments, must be presented with proper formatting and indentation to make a comment thread easy to follow and read.

### b. Reliability

After going live, the system must have a minimum uptime of 95%. Along with the system uptime, whenever the system does have downtime, whether it be unplanned or due to maintenance, there must be a mechanism in place that will cache incoming content, which will then forward the buffered content to the system once it has gone live again.

We do not expect for the system to be incredibly popular, so we have fairly low expectations for concurrent user numbers.

### c. Performance

The system will handle all incoming messages as soon as possible. There will be no data loss, and in case of server up-time, messages received in this period will be posted as soon as the system is up and running in a prioritized FIFO manner.

As the system is a leisure-based product, the level of criticality is fairly low. Still, the system should be rather responsive, as the application itself is fairly simple, and other products of the same type are pretty responsive.

### d. Supportability

The system must be properly documented, such that post-release maintenance can be performed in a cost-effective manner. There must be proper test documentation and regression suites, such that additions and changes can be performed more efficiently.

Maintenance of the system is handled by the development team. The team that receives the system will be able to report errors as issues on the GitHub repository.

### e. Implementation

The hardware platform will be a remote server operated by a third party company, and as such, neither the development team nor the users will have complete control over the server. As such, if the platform wherein the server is located has issues, the development team will not be capable of restoring the system until those issues have been resolved.

### f. Interface

The system will two different interfaces; a web-based application with a GUI, operable by normal users who wish to use the system, and a REST API that will allow an external program to publish stories and comments to the system without operating the GUI.

### g. Packaging

The system will be installed on a remote server by the development team, after which the address and any required credentials are handed over to the users, along with the location of the project's repository, such that they can establish issues found in the system that must be corrected.

### h. Legal

The system will be published on GitHub under the MIT license. The development team is fully responsible for system failures, and are required to correct failures in a timely manner.

## D. System Models

### a. Scenarios

#### Profile

##### UPDATE PROFILE SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob logs in to the website.
2. Bob wishes to update his profile, so he clicks the button that takes him to his profile settings.
3. Bob finds the section relating to the profile information he wishes to update, and writes in something new.
4. Bob clicks the button labeled *Update.*
5. The page reloads, showing Bob his profile, and confirming that the updates have been made.


##### UPDATE PROFILE FAILED AUTHENTICATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob logs in to the website.
2. Bob wishes to update his profile, so he clicks the button that takes him to his profile settings.
3. Bob finds the section relating to the profile information he wishes to update, and writes in something new.
4. Bob gets distracted, and leaves the computer for over 30 minutes, allowing the session to expire.
5. Bob clicks the button labeled *Update.*
6. The page reloads, showing the front page and informing Bob that his session expired, and he has been logged out.


##### UPDATE PROFILE FAILED VALIDATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob logs in to the website.
2. Bob wishes to update his profile, so he clicks the button that takes him to his profile settings.
3. Bob finds the section relating to the profile information he wishes to update, and clears it, leaving it empty.
4. Bob clicks the button labeled *Update.*
5. The page displays an error, informing Bob that the field cannot be left empty.

#### Upvote Posts

##### UPVOTE_POST_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob reads a post he likes and wants to upvote it.
2. Bob clicks upvote on the post.
3. Bob is met with confirmation that the post has been upvoted, by seeing that the vote count has increased and that the upvote button is highlighted.


##### UPVOTE_POST_REVERT_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob reads a post he has previously upvoted, and regrets upvoting it.
2. Bob clicks the highlighted upvote button on the post.
3. Bob is met with confirmation that his upvote has been cancelled, by seeing that the vote count has decreased and that the upvote button is no longer highlighted.


##### UPVOTE_POST_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob reads a post he likes and wants to upvote it.
2. Bob clicks upvote on the post.
3. Bob is met with an error message, telling him that he needs to be signed in to take advantage of voting functionality.


#### Upvote Comments

##### UPVOTE_COMMENT_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob reads a comment he likes and wants to upvote it.
2. Bob clicks upvote on the comment.
3. Bob is met with confirmation that the comment has been upvoted, by seeing that the vote count has increased and that the upvote button is highlighted.


##### UPVOTE_COMMENT_REVERT_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob reads a comment he has previously upvoted, and regrets upvoting it.
2. Bob clicks the highlighted upvote button on the comment.
3. Bob is met with confirmation that his upvote has been cancelled, by seeing that the vote count has decreased and that the upvote button is no longer highlighted.


##### UPVOTE_COMMENT_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob reads a comment he likes and wants to upvote it.
2. Bob clicks upvote on the comment.
3. Bob is met with an error message, telling him that he needs to be signed in to take advantage of voting functionality.


#### Downvotes

##### DOWNVOTE_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob read post and want to down vote it.
2. Bob clicks on downvote in button of the post.
3. Bob sees the downvote count has decreased.
4. Bob sees the the downvote button is highlighted because he used it.


#####  DOWNVOTE_REVERT_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob read post he previus have downvoted
2. Bob like the post and ant to remove the  downvote.
3. Bob clicks on downvote in button of the post.
4. Bob sees the downvote count has increased.
5. Bob sees the the downvote button is not highlighted anymore.


##### DOWNVOTE_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob read post and want to down vote it.
2. Bob clicks on downvote in button of the post.
3. Bob get an tooltip that tells him to login before he can downvote posts.
4. Bob sees the downvote count havent changed.

##### DOWNVOTE_FAILED_PERMISSION

###### Actors: 
Bob:User

###### Scenario:

1. Bob read post and want to down vote it.
2. Bob clicks on downvote in button of the post.
3. Bob get an tooltip that tells him he need more karma before he can downvote posts.
4. Bob sees the downvote count havent changed.


#### Comments

##### COMMENT_POST_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob sees a post that he likes and he wants to add a comment.
2. Bob clicks Comment on the post.
3. Bob writes his comment and presses the Submit comment button.COMMENT POST 


##### COMMENT_POST_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob sees a post that he likes and he wants to add a comment.
2. Bob clicks Comment on the post.
3. Bob gets a message that tells him he has to log in to the site before he can comment.


##### COMMENT_POST_FAILED_VALIDATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob sees a post that he likes and he wants to add a comment.
2. Bob clicks Comment on the post.
3. Bob presses the Submit button.
4. Bob is met with an error message that tells him he has to write something to press the button.


#### Posts
##### CREATE_POST_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob has something on his mind and wants to create a post.
2. Bob clicks the *Post* button on the *front page*.
3. Bob writes his post.
4. Bob presses the *Submit* button, and his post is created.


##### CREATE_POST_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob has something on his mind and wants to create a post.
2. Bob clicks the *Post* button on the *front page*.
3. Bob gets an *Error Message* that tells him he is not logged in.


##### CREATE_POST_FAILED_VALIDATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob has something on his mind and wants to create a post.
2. Bob clicks the *Post* button on the *front page*.
3. Bob leaves an empty post.
4. Bob presses the *Submit* button.
5. Bob gets an *Error Message* that tells him his post can't be empty.


##### DELETE_POST_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob wants to delete a post he has previously created.
2. Bob clicks the *Delete Post* button on his post.
3. Bob gets a *Message* that tells him his post has been deleted.


##### DELETE_POST_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob wants to delete a post he has previously created.
2. Bob clicks the *Delete Post* button on his post.
3. Bob gets an *Error Message* that tells him he is not logged in.

##### DELETE_POST_FAILED_ALREADY_DELETED

###### Actors: 
Bob:User

###### Scenario:

1. Bob wants to delete a post he has previously created.
2. Bob clicks the *Delete Post* button on his post.
3. Bob gets an *Error Message* that tells him his post has already been deleted.


##### UPDATE_POST_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob successfully logs in to the website.
2. Bob visits the page, finds a story that interests him, and clicks the link to be forwarded to the discussion page for that link.
3. Bob visits the link and views its contents, and either returns to read the comments already present, or immediately wishes to leave a comment regarding the link.
4. Bob types his comment into the comment box located on the page, and clicks *Submit*.
5. The page reloads, and Bob's comment has been added to the discussion.
6. Bob realizes that he made several embarrassing typos, and wishes to correct them so that he does not look foolish.
7. Bob clicks the *Update* icon on his comment, and a text box appears containing the original message.
8. Bob corrects the text, and clicks the button labeled *Update*.
9. The page reloads, and Bob's comment has been updated.


##### UPDATE_POST_FAILED_AUTH

###### Actors: 
Bob:User

###### Scenario:

1. Bob submits a comment on a topic the day prior.
2. The next day, Bob returns to the topic, but neglects to log in again.
3. Bob reads through the comments, and realizes that his comment from yesterday is filled with embarrassing typos.
4. Bob wishes to update his comment to correct the typos, and attempts to click the *Update* button on his comment, but finds that it is not present, as he has not logged in.


##### UPDATE_POST_FAILED_VALIDATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob successfully logs in to the website.
2. Bob visits the page, finds a story that interests him, and clicks the link to be forwarded to the discussion page for that link.
3. Bob visits the link and views its contents, and either returns to read the comments already present, or immediately wishes to leave a comment regarding the link.
4. Bob types his comment into the comment box located on the page, and clicks *Submit*.
5. The page reloads, and Bob's comment has been added to the discussion.
6. Bob realizes that he made several embarrassing typos, and wishes to correct them so that he does not look foolish.
7. Bob clicks the *Update* icon on his comment, and a text box appears containing the original message.
8. Bob corrects the text, but eventually decides that the contents of the text is unsalvageable, and decides to clear the field and try to leave an empty comment, pressing the *Update* button.
9. The page displays an error message, informing him that he cannot leave an empty comment.

##### READ_POST

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the website, and looks at the different links posted on the front page.
2. Bob notices a link that looks interesting, and clicks the link.
3. Bob is taken to the link's thread page, where he finds the link, along with the comments that other users have left relating to the link.
4. Bob clicks the link, and is taken to the page, where he can read or view the contents.


#### Login

##### LOGIN_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the page, and wants to gain access to features that requires you do be logged in.
2. Bob clicks *Login* in the top right of the home page.
3. Bob enters a matching *Username* and *Password*, and clicks *Login*
4. Bob receives confirmation that he is logged in, by being redirected to the front page, and his name is visible in the top right corner of the screen, where it had a login button before.

##### LOGIN_FAILED_CREDENTIALS

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the page, and wants to gain access to features that requires you do be logged in.
2. Bob clicks *Login* in the top right of the home page.
3. Bob enters a *Username* and *Password* that doesn't match, and clicks *Login*
4. Bob is met with an error message, telling him that his *Username* and *Password* doesn't match.

##### LOGIN_FAILED_VALIDATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the page, and wants to gain access to features that requires you do be logged in.
2. Bob clicks *Login* in the top right of the home page.
3. Bob enters a *Username* and doesn't fill out the *Password* field, and clicks *Login*.
4. Bob is met with an error message, telling him that both *Username* and *Password* are required fields.


#### Signup

##### SIGNUP_SUCCESS

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the page, and wants to gain access to features that requires you do be a registered user.
2. Bob clicks *Login* in the top right of the home page.
3. Bob clicks *Sign up for an account* on the login page.
4. Bob enters a *Username* a *Password*, and clicks *Sign up*.
5. Bob receives confirmation that he is logged in, by being redirected to the front page, and his name is visible in the top right corner of the screen, where it had a login button before.

##### SIGNUP_FAILED_DUPLICATE_USERNAME

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the page, and wants to gain access to features that requires you do be a registered user.
2. Bob clicks *Login* in the top right of the home page.
3. Bob clicks *Sign up for an account* on the login page.
4. Bob enters *bob1234* as his username followed by a *Password*, and clicks *Sign up*.
5. Bob is met with an error message because someone already has a user named *bob1234*, and that he should pick another username.

##### SIGNUP_FAILED_VALIDATION

###### Actors: 
Bob:User

###### Scenario:

1. Bob visits the page, and wants to gain access to features that requires you do be a registered user.
2. Bob clicks *Login* in the top right of the home page.
3. Bob clicks *Sign up for an account* on the login page.
4. Bob enters *bob1234* as his username,forgets to type in a password, and clicks *Sign up*.
5. Bob is met with an error message telling him, that all the fields are required to sign up.


### b. Use Case Model

##### UPVOTE POST

###### Actors

Bob: User

###### Flow of events

1. Bob clicks the upvote button on a post.
2. The frontend sends a request to the API indicating that the given user wants to upvote the post.
3. The backend receives the request, confirms that the user is permitted to perform the action, and registers the user vote and increments the upvote count by 1.
4. The web application receives a response, indicating that the request was successful.
5. The upvote button is highlighted, indicating that Bob has upvoted the post.

###### Entry conditions

6. Bob has logged in successfully.

7. The post is not upvoted yet by Bob.

###### Exit conditions

8. Bob has received acknowledgement that the post has been upvoted, OR
9. Bob has received an explanation indicating why the transaction could not be processed.

###### Exceptions

3a. The backend receives the request, but fails to confirm that the user is permitted to perform the action, since he is not authenticated.

###### Quality requirements

10. When the upvote has been processed, the user should be given clear indication that it has been processed, either through a success message or a graphical representation.
11. In the event of a failed upvote, the user should be presented with an error message describing why their upvote was not processed.

##### UPVOTE COMMENT

###### Actors

Bob: User

###### Flow of events

1. Bob clicks the upvote button on a comment.
2. The frontend sends a request to the API indicating that the given user wants to upvote the comment.
3. The backend receives the request, confirms that the user is permitted to perform the action, and registers the user vote and increments the upvote count by 1.
4. The web application receives a response, indicating that the request was successful.
5. The upvote button is highlighted, indicating that Bob has upvoted the comment.

###### Entry conditions

6. Bob has logged in successfully.

7. The post is not upvoted yet by Bob.

###### Exit conditions

8. Bob has received acknowledgement that the comment has been upvoted, OR

9. The user receives an error message explaining why the upvote could not be processed.

###### Exceptions

3a. The backend receives the request, but fails to confirm that the user is permitted to perform the action, since he is not authenticated.

###### Quality requirements

10. When the upvote has been processed, the user should be given clear indication that it has been processed, either through a success message or a graphical representation.
11. In the event of a failed upvote, the user should be presented with an error message describing why their upvote was not processed.

##### DOWNVOTE COMMENT

###### Actors

Bob: User

###### Flow of events

1. Bob clicks the downvote button on a post.
2. The frontend sends a request to the API indicating that the given user wants to downvote the post, or cancel the downvote, if it's already downvoted.
3. The backend receives the request, confirms that the user is permitted to perform the action, and registers the user vote and increments the downvote count by 1.
4. The frontend receives a response, indicating that the request was successful.
5. The downvote button is highlighted, indicating that Bob has downvoted the post.

###### Entry conditions

6. Bob can see a post

###### Exit conditions

7. Bob has received acknowledgement that the post has been downvoted.
8. Bob has received an explanation indicating why the transaction could not be processed.
9. Bob has received acknowledgement that his downvote has been cancelled on the post.

###### Exceptions

3a. The backend receives the request, but fails to confirm that the user is permitted to perform the action, since he is not authenticated.

###### Quality requirements

10. When the downvote has been processed, the user should be given clear indication that it has been processed, either through a success message or a graphical representation.
11. In the event of a failed downvote , the user should be presented with an error message describing why their downvote was not processed.

##### CREATE POST

###### Actors: 
User

###### Flow of events:

1. User clicks on the Post button on the front page.
2. User writes his post.
3. User presses the *Submit* button

###### Entry condition:
4. User is logged in.

###### Exit condition: 

5. The post is created and the user is directed to the post thread page. OR
6. The users is prompted a message that not all required information has been given.

###### Exceptions:

1a. the User cant create post if he is not logged in.

3a. The User cant upload empty post, get a tooltip.

###### Quality requirements:

- All messages should be received and posted as soon as possible, which in most cases should be almost instant.

##### CREATE COMMENT

###### Actors:
User

###### Flow of events:

1. User clicks the write comment button on a post.
2. User writes his comment and presses the Submit comment button.
3. The frontend sends a request to the API indicating that the given user wants to write a comment.
4. The backend receives the request, and post the comment to the database.
5. The user receives a response indicating that his comment was successfully created.

###### Entry condition:

6. User is logged in.

###### Exit condition:

7. The User is redirected to the post and can see his new comment.
8. The User gets an error message that tells him he can't write an empty comment.

###### Exceptions

3a. The User can't create a comment if he is not logged in. 
4a. If something goes wrong with the request in the backend the user is notified that the comment will be posted at a later time.

###### Quality requirements:

* The User instantly sees his new comment.


##### DELETE COMMENT

###### Actors:

User

###### Flow of events:

1. User clicks the delete comment button on a post he wants to delete
2. User confirms he wants to delete the comment.
3. A request is sent to the API indicating that the given user wants to delete a comment.
4. The backend receives the request and finds an deletes the comment from the database.
5. The user receives a response indicating that the comment was deleted.
6. The user can no longer see his comment on the post.

###### Entry Condition:

7. User is logged in.

###### Exit condition:

8. The comment is deleted.
9. User gets an error message that says post is already deleted.

###### Exceptions:

2a. The user can't delete a comment if he is not logged in.
4a. If something goes wrong with the request in the backend, the user is notified that his comment will be deleted at a later time.

###### Quality requirements:

* The comment is deleted nearly instantly from the post.

##### DELETE POST

###### Actors: 
User

###### Flow of events:

1. User clicks the *Delete* button on the post.

###### Entry condition: 
2. User is logged in and the user is the author of the post.

###### Exit condition: 

3. The post is deleted from the front page

###### Exceptions:

N/A.

###### Quality requirements:

- All deleted messages should be instantly removed from the front page.


##### UPDATE COMMENT

###### Actors:
User

###### Flow of events:

1. The user presses the update comment button on a previously written comments.
2. The user edits his comment and presses the confirm update button.
3. A request is sent to the API indicating that the user wants to update a comment.
4. The backend receives the request and finds and updates the comment in the database and. The user receives a response telling that the comment has been updated.

###### Entry Conditions:

5. The user is logged in.

###### Exit Conditions:

6. The comment is updated.

###### Exceptions:

3a. The user can't update a comment if he is not logged in.
4a. If something goes wrong with the request the user is notified that his comment will be updated at a later time.

###### Quality requirements:

* The comment is updated and shown updated nearly instantly on the post.
##### UPDATE PROFILE

###### Actor:
User

###### Flow of events:

1. User clicks the edit profile button.
2. User change what he want to change in his profile and press the update profile button.
3. The frontend sends a request to the API indicating that the given user wants to update the profile.
4. The backend receives the request, and post the user profile changes to the database.
5. The user receives a response indicating that his profile was successfully updated.

###### Entry condition:

6. User is logged in.

###### Exit condition:

7. The User is redirected to the profile view and can see his new updated profile.
8. The User gets an error message that tells him he filled out his profile wrong.
9. The User get asked if hes want to save changes if hes havent saved changes.

###### Exceptions:

2a. The User can't update profile if he is not logged in. 

###### Quality requirements:

- The User instantly sees his new update.

##### LOGIN

###### Actors: 
User

###### Flow of events:

1. User clicks *Login* in the top right corner of the home page.
2. User enters credentials and clicks *Login*

###### Entry condition:
3. User is not logged in.

###### Exit condition: 

4. The user is redirected to the front page and username is visible in top right corner. OR
5. The user is kept on the login page, given a message that username/password was incorrect/missing.

###### Exceptions:
2a. No connection to login server.

###### Quality requirements:

- Validation of login should be close to real time.

##### UPDATE POST

###### Actors:  
User

###### Flow if events:

1. User clicks the *Update* button on the post.
2. User changes the comment for the post
3. User clicks the *Update* icon

###### Entry condition:
4. User is logged in and the user is the author of the post.

###### Exit condition: 

5. The user is redirected to the thread page and can see the updated content.

###### Exceptions:

N/A

###### Quality requirements:

- The content of the post is updated instantaneously.

##### READ POST

###### Actors: 
User

###### Flow of events:

1. User clicks on a post from the front page.
2. User is taken to the link's thread page, where the user finds the link, along with the comments that other users have left relating to the link.
3. User clicks on the link, and is taken to the page, where he can read or view the contents.

###### Entry condition:
N/A

###### Exit condition: 

4. The user is taken to the links thread page.
5. By clicking the link in the thread page, the user is taken to the links page, where the user can read the content.

###### Exceptions:
3a. no connection to server.

###### Quality requirements:

- By clicking the link on the front page, the users is quickly redirected to the thread page.
- No inactive/deleted thread pages should be found on the front page.

##### SIGN UP

###### Actors: 
User

###### Flow of events:

1. User clicks *Login* in the top right corner of the home page.
2. User clicks *Sign up for an account* on the login page.
3. User enters a username and a password, then clicks *Sign up*.

###### Entry condition:
4. User is not logged in.

###### Exit condition: 

5. The user is redirected to the front page and username is visible in top right corner.
6. The user is kept on the sign up page, given one of the following messages:
  6.1. Username already taken.
  6.2. Missing fields
  6.3. (Username is invalid??) <- Security reasons?

###### Exceptions
3a. no connection to server.

###### Quality requirements:

- Creating an account should be almost instantaneously if successful.
- Proper error messages should be understandable.



# 4. Glossary

**Karma rating:** A user is rated with karma points which is earned when a user's article is either up or down voted. Each up vote increases points and down votes decreases points.
