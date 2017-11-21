# Assignment 10: Scaling

by: 

- Dennis Michaelsen

- Ebbe Vig Nielsen

- Emil Rosenius Pedersen

- Nicolai Bonderup

- Rune Vandall Zimsen

  ​
## Creation and scalling in AWS
We have in our solution chosen to work with Amason Webs Services(AWS) as cloud storing and managing tool. 

I you want to read further about using AWS we suggest to look at our [hand over document](https://github.com/ERPedersen/HackerNewsBackend/blob/master/Group6_Handover_Documentation.md).

### VM's on AWS

Our Applications subsystems reside in it's own automatically scalable instance group, which by default contains three EC2 instances that are distributed across three different subnets in three different data centers. The EC2 instances are responsible for running the application code, which is stored on persistent block level storage volumes. The total number of EC2 instances running our application code is 6, and 12 while the deployment is in process on both subsystems consecutively. 

### EC2 Instances

The EC2 instances are the different virtual machines running the backend and the frontend. As we have implemented a load balancer there will be mirrored instances of the frontend and backend. You will be able to see availability of all the instances and some further information about each specific instance.

[![Imgur](https://camo.githubusercontent.com/11b77a58b2c3ffb52adb536f7c47376f56b693b3/68747470733a2f2f692e696d6775722e636f6d2f4f5649534e62782e6a7067)](https://camo.githubusercontent.com/11b77a58b2c3ffb52adb536f7c47376f56b693b3/68747470733a2f2f692e696d6775722e636f6d2f4f5649534e62782e6a7067)

### RDS Instances

The RDS instances are the database instance and provides information about the status and availability of the database instance. The database is backed up on a regular basis. As with the EC2 instance dashboard you will be able to track availability and some further information.

[![Imgur](https://camo.githubusercontent.com/b95e657a4495f5ca965f3b884694a76ede54322a/68747470733a2f2f692e696d6775722e636f6d2f4c34316a7255392e6a7067)](https://camo.githubusercontent.com/b95e657a4495f5ca965f3b884694a76ede54322a/68747470733a2f2f692e696d6775722e636f6d2f4c34316a7255392e6a7067)

### CloudWatch

Here you will be able to see information about the system such as HTTP requests, instance health, data transfers, serve CPU usage etc. Further information can be granted if needed, and again you will have to contact us in order to get access to this information.

[![Imgur](https://camo.githubusercontent.com/bffa984dec84808d5c0ec608f20c26168a7ab22a/68747470733a2f2f692e696d6775722e636f6d2f434c61747277682e6a7067)](https://camo.githubusercontent.com/bffa984dec84808d5c0ec608f20c26168a7ab22a/68747470733a2f2f692e696d6775722e636f6d2f434c61747277682e6a7067)

### Why is scalability and loan balancing needed?

AWS and Docker swarm give us an tool to managing horizontal scaling. With horizontal scaling we get option to manage single points of heavy load and way keep an steady performance for an application with rising number of users. The solution with horizontal scaling doesn't deal with badly written code, in such a situation could a vertical upgrade of the system work, but improving the code would probably also be a good/better idea. Metaphorically we can think of the system as a bottle, horizontal scaling gives us more bottles, vertical scaling give us a bigger bottle. A horizontal scaling gives us more point of entry´s and therefor a way to handle more request to an system. 