# DEPRECATED
# aws-reservations
Pair AWS EC2 instances with reservations.

## Installation
```bash
➜  sandbox  git clone git@github.com:keboola/aws-reservations.git
➜  sandbox  cd aws-reservations 
➜  aws-reservations git:(master) curl -sS https://getcomposer.org/installer | php
➜  aws-reservations git:(master) php composer.phar install
```

## Usage

Please at first provide AWS credentials http://docs.aws.amazon.com/aws-sdk-php/guide/latest/credentials.html#environment-credentials

```shell
➜  aws-reservations git:(master) php index.php instances
+------------+------------+-------+----------+---------+------+
| Zone       | Instance   | Total | Reserved | Reserve | Sell |
+------------+------------+-------+----------+---------+------+
| us-east-1d | c3.large   | 2     | 1        | 1       | 0    |
| us-east-1e | t2.micro   | 2     | 0        | 2       | 0    |
+------------+------------+-------+----------+---------+------+

```
