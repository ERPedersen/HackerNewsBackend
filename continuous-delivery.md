# Continous Delivery Strategy

## System overview

### Introduction

Our production environment consists of three subsystems. A front-end web application built with the Typescript MVC framework - Angular 4, and a REST API/back-end application built with PHP 7.0, and a relational database system. The system is cloud based, and utilizes Amazon Web Services for all of it's server infrastructure. 

### Automatically scalable instance groups

The application subsystems reside in it's own automatically scalable instance group, which by default contains three EC2 instances that are distributed across three different subnets in three different data centers. The EC2 instances are responsible for running the application code, which is stored on persistent block level storage volumes. The total number of EC2 instances running our application code is 6, and 12 while the deployment is in process on both subsystems consecutively. 

### Load balancing

In front of each application subsystem is a dynamic load balancer, which allows us to scale horizontally in any subsystem layer across the entire system, rapidly deploy new application versions with zero downtime, automatically handle backup plans in case of power outages and protect us against nature catastrophes. If one instance is somehow terminated unexpectedly, another instance is immediately deployed with our application and rerouted through the load balancer. 

### Database management

Our database is stored on a separate AWS RDS instance, which is only available inside our virtual private cloud. RDS makes makes it easy to set up, operate, and scale a relational database in the cloud.

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

