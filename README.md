### Back-end Developer coding test from figo
Task:<br>
Write a PHP implementation that can accept any set of delivery notes and produce a list ofrecords detailing every step of the journey in the order they were visited.

### How to use it
1) Download it from github `git clone git@github.com:Dawenear/BananaTransport.git`
2) Run `composer install`
3) Run `composer dump autoload`

#### In browser
1) Open `/index.php` in your browser
2) Select file to upload
3) Click on "Process Route"
4) You should see response now

#### In terminal
1) edit `deliveryNotes.json` to whatever json you want to test
2) run `php runFromTerminal.php`
3) output will be response

### Notes
The script acts like it doesn't know start and end location. <br />
The script assumes, it gets valid unbroken chain without any place visited multiple times. <br />
It is possible to create script, that can go through same place multiple times,
but it will be a way more complicated and I tried to make it as simple and fast as possible.
In usual cases I'm doing all html with framework related templates / languages. Here I worked without framework, so I printed all html and data directly.
