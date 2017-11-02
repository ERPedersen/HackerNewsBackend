# HackerNews Handover Doc

## System overview

The purpose of the system is to provide the user with a social news website similar to Reddit. The social news network allows users to post articles related to computer science and entrepreneurship. Furthermore, it allows registered users to create their own posts and participate in discussions by leaving replies on other people's posts and comments. 

The idea is to build a system that should mimic a number of selected functionalities of https://hackernews.com. A full list of features will be provided further down. 

#### Objectives and success criteria of the project

1. The system should be available to anyone with an internet connection and a browser from a publicly visible server
2. The system should have an uptime of at least 95% over the course of a 2 month period
3. The system should be able to retrieve messages, even when the backend and database is down.

The project will be focused on the description supplied to us, and will be worked on iteratively through the various assignments we will be given throughout the semester. While we do not have a current system to describe aside from the application we are supposed to imitate, we will have functional and nonfunctional requirements detailed for our proposed system.

### Proposed System

Our system will resemble the HackerNews website and include all of its basic features eg. posting articles, commenting, up-down voting, spam-flagging and karma ratings . The system will have a basic web based GUI which will be easy to use.

*Links to repositories:*

Frontend: https://github.com/ERPedersen/HackerNewsFrontend
Backend: https://github.com/ERPedersen/HackerNewsBackend

#### B. Functional requirements

- Web based application
- All users can read content
- Registered users can post/comment on/up-vote/spam-flag articles
- Registered users with a karma score of 500+ can down vote articles
- All content must be persisted in a database
- All articles must be time stamped
- The system has to have a REST API

#### C. Nonfunctional requirements

#### a. Usability

The system will be a clone of Hackernews, and as such must have a simple graphical user interface that can be operated through most modern browsers. The simplicity of the graphical user interface is key, as we do not presume user familiarity with any similar systems, and will not be providing any sort of training in its usage.

The system must provide error messages to the user if an error has occurred, with a descriptive message to inform the user what went wrong. 

The system must present the different stories that have been posted in an orderly, compact yet understandable manner. 

The comments on any given story, along with the nested comments, must be presented with proper formatting and indentation to make a comment thread easy to follow and read.

#### b. Reliability

After going live, the system must have a minimum uptime of 95%. Along with the system uptime, whenever the system does have downtime, whether it be unplanned or due to maintenance, there must be a mechanism in place that will cache incoming content, which will then forward the buffered content to the system once it has gone live again.

We do not expect for the system to be incredibly popular, so we have fairly low expectations for concurrent user numbers.

#### c. Performance

The system will handle all incoming messages as soon as possible. There will be no data loss, and in case of server up-time, messages received in this period will be posted as soon as the system is up and running in a prioritized FIFO manner.

As the system is a leisure-based product, the level of criticality is fairly low. Still, the system should be rather responsive, as the application itself is fairly simple, and other products of the same type are pretty responsive.

#### d. Supportability

The system must be properly documented, such that post-release maintenance can be performed in a cost-effective manner. There must be proper test documentation and regression suites, such that additions and changes can be performed more efficiently.

Maintenance of the system is handled by the development team. The team that receives the system will be able to report errors as issues on the GitHub repository.

#### e. Implementation

The hardware platform will be a remote server operated by a third party company, and as such, neither the development team nor the users will have complete control over the server. As such, if the platform wherein the server is located has issues, the development team will not be capable of restoring the system until those issues have been resolved.

#### f. Interface

The system will consist of two different interfaces; a web-based application with a GUI, operable by normal users who wish to use the system, and a REST API that will allow an external program to publish stories and comments to the system without operating the GUI.nder the MIT license. The development team is fully responsible for system failures, and are required to correct failures in a timely manner.

## System architecture

Our production environment consists of three subsystems. A front-end web application built with the Typescript MVC framework - Angular 4, and a REST API/back-end application built with PHP 7.0, and a relational database system. The system is cloud based, and utilizes Amazon Web Services for all of it's server infrastructure. 

### Automatically scalable instance groups

The application subsystems reside in it's own automatically scalable instance group, which by default contains three EC2 instances that are distributed across three different subnets in three different data centers. The EC2 instances are responsible for running the application code, which is stored on persistent block level storage volumes. The total number of EC2 instances running our application code is 6, and 12 while the deployment is in process on both subsystems consecutively. 

### Load balancing

In front of each application subsystem is a dynamic load balancer, which allows us to scale horizontally in any subsystem layer across the entire system, rapidly deploy new application versions with zero downtime, automatically handle backup plans in case of power outages and protect us against nature catastrophes. If one instance is somehow terminated unexpectedly, another instance is immediately deployed with our application and rerouted through the load balancer. 

### Database management

Our database is stored on a separate AWS RDS instance, which is only available inside our virtual private cloud. RDS makes it easy to set up, operate, and scale a relational database in the cloud.

## Feature development

### Branching

While the developers are working on new features, they will be working in separate version control branches reserved for a single use case. If the use case is spanning towards different components, the use case is divided into subtasks, which are then worked on in separate branches and ultimately merged into the given feature branch. This allows us to restrict ourselves to merge only finished features into the development environment, which can then be manually tested by the person responsible for the quality assurance of the software before they are released to production.

The name of a feature branch for a given use case, must match the ID of the use case in our project management tool. This gives the team an overview over which branches are tied to which use cases, and makes it intuitive for developers to find the related use case in the project management tool, which allows for communication, finding the definition of the acceptance tests, and how to test the implemented functionality in a review situation. 

### Releasing features to the staging environment

Once a developer is finished developing an entire feature, a merge request to the staging environment is initiated, which initiates the review process. In this review, the focus is mainly on the technical aspect of the code, while still keeping the acceptance requirements in mind. The review process is as follows:

- **The first step** is performed automatically, which checks whether feature is automatically mergable with the staging environment. If the system reports, that the merge will result in conflicts, the conflicts must be fixed in the feature branch before it can pass the review process.
- **The second step** is performed by automatically triggering a deployment to our continuous integration software, which will deploy the commit to a build server, and run a configuration of unit tests, integration tests and e2e-tests. The build must pass with a code zero, meaning that the application was successfully deployed and all tests passed.
- **The third step** is performed by a developer, who is assigned to review the task. In this step, the reviewer will test that the code functions on his machine as per the use case specification, and will also review the source code line-by-line, going through a number of technical requirements such as modularity, performance, ease of reading, and code standards.

If the reviewer adds any comments, changes or additions in the review these issues are to be resolved, before the merge request is re-initiated. This is often done via pair-programming, to allow sharing of knowledge and discussion of the changes.

Once the review has passed, the code is merged into the staging branch, where it resides until the project manager and quality assurance decides to deploy a build to production. 

### Releasing features to production

When a feature is considered shippable, a release-to-production request can be initiated. Releasing features into production is done by initiating an additional merge request onto the production environment. This will trigger a build process similar to the one towards the staging environment, but this time, the focus is on the quality assurance and requirements specification.

- **The first step** is again performed automatically, which checks whether feature is automatically mergable with the production environment. If the system reports, that the merge will result in conflicts, the conflicts must be fixed in the development branch before it can pass the review process. Although this will almost never happen, the possibility is still there.
- **The second step** is again performed by automatically triggering a deployment to our continuous integration software, which will deploy the commit to a build server, and run a configuration of unit tests, integration tests and e2e-tests. The build must again pass with a code zero, meaning that the application was successfully deployed and all tests passed.
- **The third step** is performed by the project manager and the person responsible for quality assurance. In a real world example, the product owner and other key figures would be a part of this review. In this review, the functionality of the features are taken into consideration, making sure they live up to the requirements specification. 

Once the review has passed, the code is merged into the production branch, where it triggers one last build with the continuous integration software. This might be seen as a bit overkill, but it is there to ensure that the features released into production are 200% tested. If the build passes with a code zero, and automatic deployment process is initiated.

### Deployment process

Once the deployment process is initiated and the continuous integration build passes with a code zero, GitHub will automatically query a web hook, which notifies our cloud infrastructure, that a new production build is ready for release.

Here we use AWS CodeDeploy, to automatically handle the deployment process for us, using a blue/green deployment strategy. With this deployment strategy, every time we reploy a new revision, we provision a set of 3 replacement instances residing in a new automatically scalable instance group. On each instance CodeDeploy downloads and installs the latest version of our application, based on our configuration in each subsystem. If everything goes as planned, CodeDeploy performs a health check of each instance, determining whether the replacement instances are ready for deployment.

CodeDeploy then reroutes load balancer traffic from the existing set of instances running the previous version of our application to the set replacement instances running the new version. After traffic is rerouted to the new instances, the original instances are terminated after 15 minutes. 

Blue/green deployments allow us to test the new application version before sending production traffic to it. If there is an issue with the newly deployed application version, we can roll back to the previous version faster than with in-place deployments, and additionally, the instances provisioned for the blue/green deployment will reflect the most up-to-date server configurations since they are new.

## System Access & Credentials

The web application frontend for the system is available at **http://hackernews.emilrosenius.com/**. User registration is available, but a test user has been prepared which has the necessary karma to properly test the system fully.

The API address for accessing the REST endpoints is available at **http://api.hackernews.emilrosenius.com/**.

**NB!** The credentials will be sent by email to the group that has to operate our system.

## Bug Reports & Issues

Bug reports and issues will be handled through the Issues tab on either of the project repositories. When submitting an issue, please be sure to apply a label to the issue to help differentiate from the different types of submissions.

### Bug Reports

When submitting a bug report, please be sure to provide as much information as possible that could be used to reproduce and diagnose the problem. This type of information includes, but is not limited to:

- All actions taken to cause the issue
- Full error messages
- Screendumps of the error
- State information; what user is logged in, which post is being viewed, which comment is being acted upon, etc.

The more information we get, the easier it will be to correct the bug in question, so if possible, please provide as much as possible.

### Defects & Enhancements

If the system behaves in a way that does not follow requirements properly, or if there are additions or changes to the system that would be highly desirable, then please submit an issue with a corresponding label.

When submitting an issue pertaining to a defect or enhancement, please directly describe the  component in question, and the precise changes that should be made. The more detailed an explanation we get, the easier it will be to implement, without quite as much back-and-forth in order to fully understand the request.

### Questions 

Questions about the system, how it behaves, and how to use the system to perform certain tasks, should also be submitted as an issue with the correct label. We will do our best to answer inquiries about the system in as detailed a manner as possible, with a few exceptions, particularly relating to system security, as the issues will be publicly visible.



# Tests

The system comes packaged with a unit test suite in PHPUnit, as well as a suite of API service tests, runnable through Postman. The API tests will be useful for testing whether the system is properly deployed, and answers correctly to all API requests.

To use the Postman tests, import the `postman_tests.json` file, which will add a folder to the *Collections* tab of your Postman program. From there, click the arrow shown when hovering the mouse cursor over the folder, and select *Run.* Doing so will open a *Collection Runner* window, wherein you can run the full collection of requests, or run only specific requests. Each request has a number of tests tied to it, to ensure that the request has resolved properly.

Do note that these API tests are written primarily for testing local deployment, and as such, the URL written in each request reflects the URL of the local instance of the system backend. The URL can be changed to test up against the remote system, but we request that this be done sparingly; running the full suite of API requests against the remote server many times in succession could cause significant increases in operations costs, and if we find that the remote system is too heavily trafficked, it will become necessary for us to more strictly gate the amount of connections allowed at any given time.



# AWS Amazon Guide

Both our frontend and backend is hosted on Amazon AWS. There has been provided a IAM user to the group that has to operate and monitor our system.

This is a guide on how to access the Amazon Console and where to find the information needed.



### IAM user

Along with the user credentials for the frontend their will be provided a IAM user to access the Amazon Console. The email has a URL to the login page, a username and a password. You will be prompted to reset the password when login in for the first time.

![Imgur](https://i.imgur.com/M6YpobC.jpg)



### Console dashboard

When you have logged in you will be presented with the console dashboard where you will be able to find the specific service by using the search field at the top of the page.

![Imgur](https://i.imgur.com/FMLX0Hc.jpg)

You have access to three areas which are the EC2 instance dashboard, RDS instance dashboard and CloudWatch. All other services will be unavailable and you will be presented with an permission error if trying to access these parts of the AWS console. If any other services are needed, you will have to contact us to be granted access.



### EC2 Instances

The EC2 instances are the different virtual machines running the backend and the frontend. As we have implemented a load balancer there will be mirrored instances of the frontend and backend. You will be able to see availability of all the instances and some further information about each specific instance.

![Imgur](https://i.imgur.com/OVISNbx.jpg)



### RDS Instances

The RDS instances are the database instance and provides information about the status and availability of the database instance. The database is backed up on a regular basis. As with the EC2 instance dashboard you will be able to track availability and some further information.

![Imgur](https://i.imgur.com/L41jrU9.jpg)



### CloudWatch

Here you will be able to see information about the system such as HTTP requests, instance health, data transfers, serve CPU usage etc. Further information can be granted if needed, and again you will have to contact us in order to get access to this information.

![Imgur](https://i.imgur.com/CLatrwh.jpg)

