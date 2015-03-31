# aws-reservations
Pair AWS EC2 instances with reservations.

## installation
```bash
➜  sandbox  git clone git@github.com:keboola/aws-reservations.git
➜  sandbox  cd aws-reservations 
➜  aws-reservations git:(master) curl -sS https://getcomposer.org/installer | php
➜  aws-reservations git:(master) php composer.phar install
```

```shell
➜  aws-reservations git:(master) php index.php instances
+------------+------------+-------+----------+---------+------+
| Zone       | Instance   | Total | Reserved | Reserve | Sell |
+------------+------------+-------+----------+---------+------+
| us-east-1d | c3.large   | 2     | 1        | 1       | 0    |
| us-east-1e | t2.micro   | 2     | 0        | 2       | 0    |
+------------+------------+-------+----------+---------+------+

```
