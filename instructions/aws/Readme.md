# Amazon Web Services

Part of my master thesis focuses on passage reranking with BERT. As I did not possess computationally strong enough equipment to be able to run BERT, I decided to utilize the products offered by Amazon Web Services. To be precise I made use of [Amazon SageMaker](https://aws.amazon.com/sagemaker/) and [Amazon EC2](https://aws.amazon.com/ec2/). 

In this section I will go in detail on how I made use of those services. How to initialize and configure your workspace, how to get started within SageMaker, and how to be able to run scripts (in the background) on Amazon's cloud EC2. My code can be found [here](https://github.com/Tomjg14/Master_Thesis_MSMARCO_Passage_Reranking_BERT/tree/master/src/aws).

Page Content:
- [Important Information](#important-information)
- [Amazon SageMaker](#amazon-sagemaker)
- [Amazon EC2](#amazon-ec2)

External pages that were consulted:
- [AWS Amazon SageMaker](https://aws.amazon.com/sagemaker/)
- [AWS Amazon EC2](https://aws.amazon.com/ec2/)
- [AWS Amazon Free Tier](https://aws.amazon.com/free/?all-free-tier.sort-by=item.additionalFields.SortRank&all-free-tier.sort-order=asc)
- [AWS Amazon Instance Types](https://aws.amazon.com/ec2/instance-types/)
- [Medium AWS Credits](https://medium.com/@jaychapel/9-ways-to-get-aws-credits-9a85e0f452a1)
- [What is SageMaker](https://docs.aws.amazon.com/sagemaker/latest/dg/whatis.html)
- [SageMaker Features](https://docs.aws.amazon.com/sagemaker/latest/dg/whatis.html#whatis-features)
- [Amazon SageMaker Studio Notebooks](https://docs.aws.amazon.com/sagemaker/latest/dg/notebooks.html)
- [AWS Amazon SageMaker Studio UI Overview](https://docs.aws.amazon.com/sagemaker/latest/dg/studio-ui.html)
- [Dataschool EC2 Server](https://dataschool.com/data-modeling-101/creating-an-ec2-server/)
- [Dataschool Jupyter Notebook on EC2 Server](https://dataschool.com/data-modeling-101/running-jupyter-notebook-on-an-ec2-server/)


## Important Information

Before I explain the products from Amazon Web Services that I used, I would like to provide some important information on AWS. Making use of AWS is not entirely free and this section should prevent you spending more money than is needed to make use of AWS. 

AWS does offer the [Free Tier](https://aws.amazon.com/free/?all-free-tier.sort-by=item.additionalFields.SortRank&all-free-tier.sort-order=asc) that lets you explore more than 60 AWS products without costs. There are three types of free offers available depending on the product used: **Always Free**, **12 Months Free**, **Trials**. The **Always Free** offers do not expire and are available to all AWS customers, the **12 Monthes Free** offers can be used free for 12 months from you initial sign-up date to AWS and the **Trial** offers are short-term and start from the date you activate a particular service. To find more information on these offers and what offers are available for your product of need visit the link above. 

During my thesis I made use of Amazon SageMaker for Machine Learning. For this product the Free Trial offer type exists and this trial consists out of the following: 250 hours per month of t2.medium notebook usage for the first two months; 50 hours per month of m4.xlarge for training for the first two months; 125 hours per month of m4.xlarge for hosting for the first two months. As you can see you can only make use of certain instance types for free and this is only for the a certain amount of hours for the first two months. More on the specifics per instance type can be found [here](https://aws.amazon.com/ec2/instance-types/). 

During my thesis I made use of an AWS account from a fellow student and so my trial period had already expired. Moreover, the free to use instances did not possess computing power strong enough for me to run my experiments. Which meant that every experiment I ran on SageMaker (or a direct EC2 server) did cost me money. Luckily I could use the AWS credits my fellow student left me on her account. 

So what are AWS credits? You can buy credits online and redeem them on you AWS account. These credits are then applied to your AWS cloud bills to cover costs that are made by making use of the AWS services. These credits are applied to your bills until you ran out of them or they have expired. One way to get these AWS credits is via [Ebay](https://www.ebay.com/p/2268557810). But you can also earn free AWS credits if you are either an educator or a student. Educators can earn $200 AWS credits when they are linked to a member institution or $75 AWS credits if they work at non-member institutions. Students can earn $50 AWS credits when studying at a member institution or $35 AWS credits when studying at a non-member institution. Aside from these options, there are several other ways you can get AWS credits. This nice [Medium article](https://medium.com/@jaychapel/9-ways-to-get-aws-credits-9a85e0f452a1) lists those options.

In case you cannot make use of the Free tier options and money is an issue. You should always be aware of your actions when making use of AWS. For example, when you make use of Amazon SageMaker and you create a new instance (more information in the next section). Be aware of the instance type you have selected. Every instance has different costs related to it. If you only mean to perform some simple computations and time is no issue, you be better of making use of some cheaper instance types that only include CPU's instead of GPU's. Or some instance with less memory as you do not need instances that deliver fast performance for workloads that process large data sets in memory. So always check for the best instance type before starting your instance. This can save you some money when working with AWS. 

Finally, it is very important that you always properly shutdown any AWS product you use. This has cost me a lot of money already, so for the sake of emphasis I will repeat this: **DO NOT FORGET TO SHUTDOWN ANY AWS PRODUCTS!** When you make use of Amazon SageMaker you will always need to manually start your instance. From the moment you have started the instance, Amazon will keep track of the costs you have made by running this specific instance. You then need to manually shutdown this instance to stop Amazon keeping track of the costs. The same counts when you make use of the Amazon EC2 servers directly. You can keep track of the costs you have made by going to your account page when signed-in to AWS. AWS offers a [Cost Explorer](https://console.aws.amazon.com/billing/home?#/costexplorer) that helps visualizing the costs you have made so far and they even provide forcasts of the costs you will make at the end of the month based on your current costs. So keep an eye on this page when working with AWS.

## Amazon SageMaker

_"Amazon SageMaker is a fully managed service that provides every developer and data scientist with the ability to build, train, and deploy machine learning (ML) models quickly."_ 

Amazon SageMaker is Amazon's product for your data science projects. Amazon SageMaker Studio is a "machine learning Integrated Development Environment (IDE) for building, training, and debuggin models, tracking experiments, deploying models, and monitoring their performance". SageMaker has many different [features](https://docs.aws.amazon.com/sagemaker/latest/dg/whatis.html#whatis-features). For my thesis I only made use of [Amazon SageMaker Studio Notebooks](https://docs.aws.amazon.com/sagemaker/latest/dg/notebooks.html). 

Amazon SageMaker Studio Notebooks are similar to Jupyter Notebooks, but you run these notebooks on an AWS environment. This environment is defined by the following:
- Instance type: This is the hardware configuration the notebook runs on. The configuration includes the number and type of processors (vCPU and GPU), and the amount and type of memory. I have discussed this in the previous section. For my thesis it was important that the instance I used had a GPU processor to be able to run BERT. It is important that you take a close look at which instance suits your task best as the instance type determines the pricing rate.
- SageMaker image: A container image that is compatible with SageMaker Studio. The image consists of the kernels, language packages, and other files required to run a notebook in Studio.
- Kernel: The process that runs the code contained in the notebook. A kernel is defined by a Conda environment.

You can change any of these resources from within the notebook.

The Amazon SageMaker Studio interface extends the JupyterLab interface, so if you are familiar with JupyterLab you will notice the similarity between the two. However, there are some additions to the interface. It is recommended that you familiarize yourself with the interface before creating or using a notebook. More information coun be found [here](https://docs.aws.amazon.com/sagemaker/latest/dg/studio-ui.html).

### Get Started 

To get started follow this [link](https://signin.aws.amazon.com/signin?redirect_uri=https%3A%2F%2Fconsole.aws.amazon.com%2Fconsole%2Fhome%3Fnc2%3Dh_mo%26state%3DhashArgs%2523%26isauthcode%3Dtrue&client_id=arn%3Aaws%3Aiam%3A%3A015428540659%3Auser%2Fhomepage&forceMobileApp=0&code_challenge=arCG73DRzVdf6aTNTm-erOh4NJLPdC8PWZQ7JOABRKc&code_challenge_method=SHA-256) to either sign-in with AWS or create a new AWS account. When you have succesfully signed-in you will be redirected to the AWS Management Console. Here you see a list of all services. Select **Amazon SageMaker**, which can be found under **Machine Learning**. The Amazon Sagemaker Studio Control Panel opens from which you can select **Notebook Instances** under the section **Notebook**. Select this option and you will be redirected to a panel where you can create a new notebook instance.

To create a new notebook instance press the orange button named **Create notebook instance**. There are several options for which detailed information can be found [here](https://docs.aws.amazon.com/sagemaker/latest/dg/howitworks-create-ws.html). For now simply give your new instance a name and pick the desired instance type. Futhermore, click on **Additional configuration** where you can decide on the volume size of your instance. This means how much storage you will have in your instance. Specify the desired volume size and click **Create notebook instance** at the bottom of the page. The instance will be created and displayed in the notebook instance panel. If the status has changed to ```InService``` it is read to use. You can now open the instance in two ways: **Open Jupyter** or via **Open JupyterLab**. The former will open the classic Jupyter dasboard while the latter will open the JupyterLab dashboard. 

When you are finished using your instance it is important that you select the instance in the notebook instances panel and manually **Stop** it by pressing the **Action** button and selecting the **Stop** option. Otherwise, the instance is still running and this will cost you money. If you come back to Sagemaker you can also start your instance this way, but then by selecting the **Start** option.

## Amazon EC2

_"Amazon Elastic Compute Cloud (Amazon EC2) is a web service that provides secure, resizable compute capacity in the cloud. It is designed to make web-scale cloud computing easier for developers."_ 

Previous section helped explain how to work with Amazon Sagemaker notebooks. While these notebooks are excellent to work with, they will only run when your computer is still running as well. Sometimes you want to run a model for multiple hours or even days and in those cases you would like to be able to shutdown your computer while the model keeps on running. This is where the EC2 servers from AWS come in handy. These servers make it possible to run scripts while your own computer is not running. 

### Get Started

**The following instructions are from [dataschool.com](https://dataschool.com/data-modeling-101/creating-an-ec2-server/)**

#### Create an AWS account

To get started follow this [link](https://signin.aws.amazon.com/signin?redirect_uri=https%3A%2F%2Fconsole.aws.amazon.com%2Fconsole%2Fhome%3Fnc2%3Dh_mo%26state%3DhashArgs%2523%26isauthcode%3Dtrue&client_id=arn%3Aaws%3Aiam%3A%3A015428540659%3Auser%2Fhomepage&forceMobileApp=0&code_challenge=arCG73DRzVdf6aTNTm-erOh4NJLPdC8PWZQ7JOABRKc&code_challenge_method=SHA-256) to either sign-in with AWS or create a new AWS account. 

#### Creating an EC2 instance

On the AWS Management Console select the **EC2** product under **Compute**. On the next page press the orange **Launch instance** button to begin creating an EC2 server. 

#### Picking an operating system

There are several operating systems available for your EC2 server to run on. You can choose between Windows or Linux. More information on the operating systems can be found [here](https://aws.amazon.com/marketplace/b/2649367011). I picked the Ubuntu Server 18.04. 

#### Chosing an instance type

Next you will have to choose an instance type like you had to do with SageMaker. This determinse how much hardware is dedicated to your server. As I wanted to run BERT and thus needed an instance with a GPU I decided on p2.xlarge as the instance type for my EC2 server.

#### Review and Launch

Next press the blue button named **Review and Launch**. Here you can review your EC2 server and if needed increase its capabilities. It is wise to increase its storage capabilities under the **Storage** section. If you are finished you can press the blue **Launch** button to launch your EC2 server.

#### Creating an SSH key

When prompted to create an SSH key, you must "Create a new key pair" and download the pair to finish creating your EC2 instance.

This SSH key pair will allow you to connect remotely to your server from the command line. In order to do this you may have to change the permissions for your key file. This can be done with:

```
Sudo chmod 400 /Path/YourKey.pem
```

### Running Jupyter Notebook on an EC2 Server

**The following instructions are from [dataschool.com](https://dataschool.com/data-modeling-101/running-jupyter-notebook-on-an-ec2-server/)**

#### Create an EC2 instance

Follow the previous section on how to create an EC2 instance.

#### Customize your EC2 server for Jupyter

To run your Jupyter Notebook on your EC2 server, you are going to need to add a new security group. On the [EC2 instance page](https://eu-west-1.console.aws.amazon.com/ec2/v2/home?region=eu-west-1#Instances:sort=desc:instanceState) go to the "Security Groups" section.

Here you need to create a new security group by clicking "Create Security Group" on the top of the page.

From there you need to give the group three properties.

Use "Add Rule" to add new Security Group rules. You will need three new rules:
- The SSH rule will allow to connect to the server via SSH.
- The HTTPS rule will allow to connect the EC2 to a website.
- The Custom TCP rule determines which port to run the website on.

Setting the source to anywhere will allow you to access your Jupyter Notebook from any IP address. You can limit access by entering a custom IP into the source.

Once you have all of that set "Create" this new security group.

Next go back to the EC2 instances page.

Select the EC2 instance you are going to be using for your Jupyter Notebook, go into "Actions", "Networking" and click "Change Security Groups".

In this menu, select the security group that you just created and then click "Assign Security Group".

#### Connecting to your EC2

By clicking "Connect" at the top of the EC2 instances page, Amazon will provide a list of instructions on connect to your EC2 server.

*Note: you will need to have an SSH Client on your computer. All Macbooks come with SSH, Windows users may have to install an SSH Client (like PuTTY). On your command line you can check if you have an SSH client by typing "SSH" and hitting enter and checking if your terminal recognizes the command.*

I was working from a Windows computer and made use of PuTTY as a SSH client to connect to my EC2 server. The next couple of instructions will help you connect via PuTTY, so you can skip these instructions if you are not making use of PuTTY. All instructions on how to connect can be found [here](https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/AccessingInstances.html?icmpid=docs_ec2_console). More detailed instructions on using PuTTY can be found [here](https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/putty.html).

Before you can make use of Putty to connect to the EC2 server, you will need to convert your private key by making use of **PuTTYgen**.

PuTTY does not natively support the private key format for SSH keys. PuTTY provides a tool named PuTTYgen, which converts keys to the required format for PuTTY. You must convert your private key (.pem file) into this format (.ppk file) as follows in order to connect to your instance using PuTTY.

1. From the **Start** menu on your computer, choose **All Programs, PuTTY, PuTTYgen**. 
2. Under **Type of key to generate**, choose **RSA**. If you're using an older version of PuTTYgen, choose **SSH-2 RSA**.
3. Choose **Load**. By default, PuTTYgen displays only files with the extension ```.ppk```. To locate your ```.pem``` file, choose the option to display files of all types.
4. Select your ```.pem``` file for the key pair that you specified when you launched your instance and choose **Open**. PuTTYgen displays a notice that the ```.pem``` file was successfully imported. Choose **OK**.
5. To save the key in the format that PuTTY can use, choose **Save private key**. PuTTYgen displays a warning about saving the key without a passphrase. Choose **Yes**.
6. Specify the same name for the key that you used for the key pair and choose **Save**. PuTTY automatically adds the ```.ppk``` file extension.

Your private key is now in the correct format for use with PuTTY. You can now connect to your instance using PuTTY's SSH client.

1. Start PuTTY (from the **Start** menu, choose **All Programs, PuTTY, PuTTY)**.
2. In the **Category** pane, choose **Session** and complete the following fields:

    - In the **Host Name** box, do the following:
        - (Public DNS) To connect using your instance's public DNS name, enter ```my-instance-operating-system@my-instance-public-dns-name```.
        - e.g.: ```ubuntu@ec2-18-222-233-183.us-east-2.compute.amazonaws.com```
     - Ensure that the **Port** value is 22.
     - Under **Connection Type**, select **SSH**.
  
3. In the **Category** pane, expand **Connection**, expand **SSH**, and then choose **Auth**. Complete the following:

    - Choose **Browse**.
    - Select the ```.ppk``` file that you generated for your key pair and choose **Open**.
    - (Optional) If you plan to start this session again later, you can save the session information for future use. Under **Category**, choose **Session**, enter a name for the session in **Saved Sessions**, and then choose **Save**.
    - Choose **Open**.
    
4. If this is the first time you have connected to this instance, PuTTY displays a security alert dialog box that asks whether you trust the host to which you are connecting: choose **Yes**.

Now when you connect via PuTTY you will be greeted with the following message: "```Welcome to Ubuntu 18.04.2 LTS```" or whatever Operating System you installed.

#### Installing Jupyter Notebook

Once you are connected to your EC2, you now need to install Jupyter. The easiest way to do this is to download [Anaconda distribution](https://www.anaconda.com/products/individual) that matches your Operating System (if you are following along using Ubuntu, this is the Linux version).

To download the Anaconda distribution to your EC2, copy the "installer" link and run on PuTTY command prompt:

```
wget copied_installer_address
```

Once the file is downloaded run ```bash```on the file that was downloaded to install it, e.g.:

```
bash Anaconda3-2019.03-Linux-x86_64.sh
```

#### Configuring Jupyter Notebook's Path

First, you need to add Jupyter to the system's path (you can check if it is already on the path by running: ```which python```, if no path is returned you need to add the path). to add Jupyter functionality to your terminal, add the following line of code to your ```.bashrc``` file:

```
export PATH=/home/ubuntu/anaconda3/bin:$PATH
```

After saving this change, you now need to run:

```
source .bashrc
```

To cause the changes to take effect. Now running which python should return the path to the python folder in Anaconda.

#### Configuring Jupyter Notebook settings

First, you need to create the Jupyter configuration file. In order to create that file, you need to run:

```
jupyter notebook --generate-config
```

After creating your configuration file, you will need to generate a password for your Jupyter Notebook using ipython:

Enter the IPython command line:

```
ipython
```

Now follow these steps to generate your password:

```
from IPython.lib import passwd

passwd()
```

You will be prompted to enter and re-enter your password. IPython will then generate a hash output, **COPY THIS AND SAVE IT FOR LATER**. You will need this for your configuration file.

Next go into your jupyter config file:

```
cd .jupyter

nano jupyter_notebook_config.py_
```

And add the following code:

```
conf = get_config()

conf.NotebookApp.ip = '0.0.0.0'
conf.NotebookApp.password = u'YOUR PASSWORD HASH'
conf.NotebookApp.port = 8888
```

#### Create a directory for your notebooks

This step is easy. In order to make a folder to store all of your Jupyter Notebooks simply run:

```
mkdir MyNotebooks
```

You can call this folder anything, for this example lets call it "MyNotebooks".

#### Connecting to your EC2 Jupyter Server

You should now be ready to run your notebook and access your EC2 server. To run your Notebook simply run the command:

```
jupyter notebook
```

From there you should be able to access your server by going to:

```
https://(your AWS public dns):8888/
```

For example it should look like:

```
https://ec2-18-222-233-183.us-east-2.compute.amazonaws.com:8888/
```

The page that is loaded asks for your IPython password. Enter the password and you should be greeted with your full Jupyter Notebook system.

### Running Python Script on an EC2 Server

Instead of using Jupyter Notebook on your own EC2 server, you can also run your own python scripts on the server. The nice thing about this is that you can make use of the ```screen``` command of Linux to run your script in the background and thus shutdown your own computer while it is still running on the EC2 server. 

You can either connect to the EC2 server via PuTTY and create the python script directly on the server. Or use WinSCP to put any script you have stored locally on the EC2 server.

When you have your python script loaded on the EC2 server, execute it by typing the following command:

```
screen your-python-script.py
```

A screen session will be started, which you can keep running by detaching the session. To detach the session type "Ctrl+a" immediately followed by "d" and you will be back to the terminal seeing the message that the Screen is detached. Now you can safely logout and your session will be left alive.

To resume any detached screen sessions type "screen -r" in the terminal to see any running sessions.
Next to open the preferred session type "screen -r <pid.tty.host>" where you type the session id directly after "-r". 

More information on screen can be found [here](https://www.tecmint.com/keep-remote-ssh-sessions-running-after-disconnection/).


[Homepage](https://tomjg14.github.io/Master_Thesis_MSMARCO_Passage_Reranking_BERT/)
