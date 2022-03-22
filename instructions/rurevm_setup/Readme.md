# Getting Started

This page contains instructions on how I started out and what initial setup I needed to perform.
I was able to work with a server from the Radboud University named *rurevm*. Therefore, this page is useful when also working from that server.

## Connect to Rurevm

I am working from a Windows computer, so in order to connect with rurevm I make use of [Putty](https://www.putty.org/). When you open Putty you see a list with different categories to the left. Click on Session, which is where you can specify the IP address and port of the server with which you want to connect. First you need to connect to your science account from the Faculty of Science. Under Host Name (or IP address) you type:

```
your_science_user_name@lilo.science.ru.nl
```

And under port you write the number 22.
You can then decide to save this session by naming it under **Saved Sessions** and pressing **Save**.
The next time you can then simply load this session by selecting the session name and pressing **Load**. To connect to your science account press **Open**.
A terminal will be opened and you will be asked to type in the password of your science account.
This will give you access to your science account from which you can ssh to the rurevm.
In the terminal type the following to connect to rurevm:
```
ssh 'rurevm_username@rure-vm.cs.ru.nl'
```
Again, you will be asked for your password after which you will be redirected to the rurevm server.

[Homepage](https://tomjg14.github.io/Master_Thesis_MSMARCO_Passage_Reranking_BERT/)
