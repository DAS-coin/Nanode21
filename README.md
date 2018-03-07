# Nanode21

Simple PHP-based monitor for a Nano network node.
It connects to a running node via RPC and displays it's status on a simple webpage:

![phpNodeXRaiImage](https://github.com/stefonarch/phpNodeXRai/blob/master/preview.png) 

Currently, the following information is displayed:
* Information text block
* Current block number
* Number of unchecked blocks
* Number of peers
* Account information of the node's account and the server
* Basic Nano value information


## Installation

To use nanode21, you will need to setup a RaiBlocks node and a webserver on the same machine.
Instructions for setting up in a simple way a node can be found [here](http://nanode21.cloud/setupnode.htm)
This How-to includes a [script](https://gist.github.com/stefonarch/61d21152a0b71341e4c4a1b5a0df7795) which will configure RPC settings and install some basic comands 
to control the node.

You will need to install and configure nginx and phpfm to run this stuff.

* Create a directory in your webserver directory, e.g. `/var/www/html/stats`.
* Run `git clone https://github.com/https://github.com/stefonarch/Nanode21 /var/www/html/stats`
* This frontend should be visible at http://[your-ip-address]/stats/
* Modify settings in /modules/config.php at your needs. Note that the IP-address and the port for the RPC  in the file `config.php` have to  match the entries in `RaiBlocks/config.json`
* You might need to additionally install php7.0-curl, i.e. `sudo apt-get install php7.0-curl`

Feel free to change your representative to my RaiBlocks node `xrb_1i9ugg14c5sph67z4st9xk8xatz59xntofqpbagaihctg6ngog1f45mwoa54` to support further decentralization within the Nano network.






