
# How To Transfer Files Between Local Machine and Rure-vm

This page will explain how to transfer files from the rurevm server back to your local machine and vice versa. As with all instructions in this repository, for these instructions to work you are expected to own a science account from the Faculty of Science from the Radboud university of Nijmegen. As well as an account on the rurevm server. 

These instructions are meant for users working with Windows, Winscp and Putty. So you are expected to have installed Winscp and Putty on your local machine.

Pages that were consulted:

[https://stackoverflow.com/questions/5492023/transfer-files-to-from-session-im-logged-in-with-putty](https://stackoverflow.com/questions/5492023/transfer-files-to-from-session-im-logged-in-with-putty)

## File Transfer From Local Machine To RureVM

First of all, on your local machine start a command prompt window. Via the command prompt use the following commando to transfer the specific file to your Radboud science account:

```
pscp /path/to/file/filename.extension your_science_account_username@lilo.science.ru.nl:/path/to/file/filename.extension 
```

You might be asked to enter a password, this is your science account password.
After entering your password, the file will be transfered to your science account.

Now use putty to ssh to your science account.

When connected to your science account via putty use the following commando to transfer the file to your rurevm account:

```
pscp /path/to/file/filename.extension your_rure_vm_account_username@rure-vm.cs.ru.nl:/path/to/file/filename.extension
```

Again, you might be asked to enter your password, this is your rure-vm account password.
After entering your password, the file will be transfered to your rure-vm account

## File Transfer From RureVM to Local Machine

To transfer a file back from your rurevm account to your local machine you use the same commando's as you would to transfer from local machine to rurevm. But instead you use the inverse.

So start by using putty to connect to your science account.

When connected use the following commando to transfer a file from the rurevm server to your science account:

```
pscp your_rure_vm_account_username@rure-vm.cs.ru.nl:/path/to/file/filename.extension /path/to/file/filename.extension
```

You will be asked for your password, enter your rurevm password and the file will be transfered to your science account.

Next, open a command prompt on your local machine and use the following commando to transfer the file from your science account to your local machine:

```
pscp your_science_account_username@lilo.science.ru.nl:/path/to/file/filename.extension /path/to/file/filename.extension
```

You might be asked for your science account password, enter it and the file will be transfered.

[Homepage](http://tomjanssengroesbeek.nl/Master_Thesis_CoAs_BM25_BERT/)
