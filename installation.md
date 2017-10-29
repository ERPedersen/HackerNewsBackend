# Installation Guide

### 1. Clone this project
Clone this project to your preferred location on your computer. You will have to use this location in a step later on.

### 2. Install Vagrant & VirtualBox
We're going to be using Homestead as our virtual development environment. Actually, it is a VM for running Laravel,
but we need roughly the same technologies as Laravel does, so we will stick with it, since it's easy to set up.
Before launching your Homestead environment, you must install [VirtualBox 5.1](https://www.virtualbox.org/)
as well as [Vagrant](https://www.vagrantup.com/). All of these software packages provide easy-to-use visual 
installers for all popular operating systems.

### 3. Installing the Homestead Vagrant Box
Once VirtualBox and Vagrant have been installed, you should add the `laravel/homestead` 
box to your Vagrant installation using the following command in your terminal. It will take a 
few minutes to download the box, depending on your Internet connection speed:

```bash
vagrant box add laravel/homestead
```

### 4. Installing Homestead
You can install Homestead by simply cloning the repository. Consider cloning the repository into a 
Homestead folder within your "home" directory:

```
cd ~
git clone https://github.com/laravel/homestead.git Homestead
cd Homestead
git checkout v6.2.2
```

Once you have cloned the Homestead repository, run the `sh init.sh` command from the Homestead directory to create 
the Homestead.yaml configuration file. The Homestead.yaml file will be placed in the Homestead directory:

```bash
sh init.sh
```

After initializing the Homestead.yaml file. Open it replace it's contents with the content below, and replace the values
encapsulated in `<` and `>`.

- `<PATH TO YOUR SSH KEY>`: *The path to your SSH key, that you are using on GitHub. Typically **C:\Users\<USERNAME>\.ssh\id_rsa** on windows.*
- `<LOCAL PATH TO THIS PROJECT>`: *The path to this repository on your local machine*

```yaml
---
ip: "192.168.10.10"
memory: 2048
cpus: 1
provider: virtualbox
mariadb: true

authorize: <PATH TO YOUR SSH KEY>

keys:
    - <PATH TO YOUR SSH KEY>

folders:
    - map: <LOCAL PATH TO THIS PROJECT>
      to: /home/vagrant/repository/hackernews
    
sites:
    - map: api.hackernews.dev
      to: /home/vagrant/repository/hackernews

databases:
    - hackernews
```

Once you have set up your Homestead.yaml file, run the following command to provision the Homestead VM:
```
cd ~/Homestead
vagrant up
```

### 5. Setting up your hosts file
We need to map the `api.hackernews.dev` domain to the new virtual machine. 
Navigate to **C:\System32\drivers\etc** , open your hosts file and add the following line to your hosts file:
```
192.168.10.10 api.hackernews.dev
```

To flush your DNS cache, press `<WIN>+<R>`, type in `cmd.exe`, press `<ENTER>` and run the following command:
```
ipconfig -flushdns
```

### 6. Installing composer dependencies
This project utilizes [Composer](https://getcomposer.org/) to manage external dependencies, much like [NPM](https://www.npmjs.com/).
In order to install the composer dependencies required to run this project, run the following commands:
```
cd ~/Homestead
vagrant ssh

# In your VM:
cd /home/vagrant/repository/hackernews
composer install
composer update
composer dump-autoload
```

That's it. You can now access the HelloWorld endpoint at `api.hackernews.dev/api`.