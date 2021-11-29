# bot-calculate-position with PHP

Simple pattern bot logic calculate X / Y position

easy to use just run 1 command:
````
php -f movement-bot.php '{movement}'
````

Please follow format movement:
````
Start with [R / L / W]
R is mean Right
L is mean Left
W is mean Walk

Behind W is require only positive number

E.G.
Command: php -f movement-bot.php 'RW1000LLRRW1' 
Result: X: 1001 Y: 0 Direction: East
````
